<?php
namespace iiif\context;

use iiif\tools\IiifHelper;

/**
 * Implementation of the context processing algorithm of the JSON-LD API version 1.1.
 * Expansion, Compaction and Flattening algorithms are not implemented.
 *
 *
 * @author lutzhelm
 *        
 */
class JsonLdProcessor {

    /**
     * Delimiters of the generic URI components.
     * See https://tools.ietf.org/html/rfc3986#section-2.2
     *
     * @var array
     */
    const GEN_DELIM = [
        ":",
        "/",
        "?",
        "#",
        "[",
        "]",
        "@"
    ];

    const PROCESSING_MODE_JSON_LD_1_0 = "json-ld-1.0";

    const PROCESSING_MODE_JSON_LD_1_1 = "json-ld-1.1";

    const VERSION_1_1 = 1.1;

    /**
     * IIIF image api version 1.1 context URI is http://library.stanford.edu/iiif/image-api/1.1/context.json which is no longer available via HTTP and results in a 404 error.
     * https://iiif.io/api/image/1/context.json provides the content of the context. See https://github.com/IIIF/api/issues/1300 .
     *
     * @var array Array with the original URI as key and a replacement URL as value
     */
    const REDIRECTIONS = [
        "http://library.stanford.edu/iiif/image-api/1.1/context.json" => "https://iiif.io/api/image/1/context.json"
    ];

    protected $processingMode;

    protected $dereferencedContexts;

    protected $knownIiifContexts;

    public function __construct($requestKnownIiifContexts = false) {
        $this->dereferencedContexts = array();
        $this->knownIiifContexts = array();
        if (! $requestKnownIiifContexts) {
            $this->knownIiifContexts["http://www.w3.org/ns/anno.jsonld"] = __DIR__ . "/../../../resources/contexts/annotation-context.json";
            $this->knownIiifContexts["http://www.shared-canvas.org/ns/context.json"] = __DIR__ . "/../../../resources/contexts/presentation-context-1.json";
            $this->knownIiifContexts["http://iiif.io/api/presentation/1/context.json"] = __DIR__ . "/../../../resources/contexts/presentation-context-1.json";
            $this->knownIiifContexts["http://iiif.io/api/presentation/2/context.json"] = __DIR__ . "/../../../resources/contexts/presentation-context-2.json";
            $this->knownIiifContexts["http://iiif.io/api/presentation/3/context.json"] = __DIR__ . "/../../../resources/contexts/presentation-context-3.json";
            $this->knownIiifContexts["http://iiif.io/api/presentation/3/combined-context.json"] = __DIR__ . "/../../../resources/contexts/presentation-combined-context-3.json";
            $this->knownIiifContexts["http://iiif.io/api/image/1/context.json"] = __DIR__ . "/../../../resources/contexts/image-context-1.json";
            $this->knownIiifContexts["http://iiif.io/api/image/2/context.json"] = __DIR__ . "/../../../resources/contexts/image-context-2.json";
            $this->knownIiifContexts["http://iiif.io/api/image/3/context.json"] = __DIR__ . "/../../../resources/contexts/image-context-3.json";
            $this->knownIiifContexts["http://iiif.io/api/search/1/context.json"] = __DIR__ . "/../../../resources/contexts/search-context-1.json";
            $this->knownIiifContexts["http://iiif.io/api/auth/1/context.json"] = __DIR__ . "/../../../resources/contexts/auth-context-1.json";
        }
    }

    private function loadUnknownContext($context) {
        if (array_key_exists($context, self::REDIRECTIONS)) {
            $context = self::REDIRECTIONS[$context];
        }
        if (array_key_exists($context, $this->knownIiifContexts)) {
            return file_get_contents($this->knownIiifContexts[$context]);
        }
        if (strpos($context, "http://example.org") === 0) {
            // only for testing purposes
            return '{"@context":{}}';
        }
        return IiifHelper::getRemoteContent($context);
    }

    public function processContext($localContext, JsonLdContext $activeContext, $remoteContexts = array()) {
        // Numbering as in draft https://w3c.github.io/json-ld-api/#algorithm on 2019-01-10
        // 1)
        $result = clone $activeContext;
        // 2)
        if (!JsonLdHelper::isSequentialArray($localContext)) {
            $localContext = [
                $localContext
            ];
        }
        // 3)
        foreach ($localContext as $context) {
            // 3.1)
            if ($context == null) {
                $result = new JsonLdContext($this);
                // TODO handle base iri
                continue;
            }
            // 3.2)
            if (is_string($context)) {
                // TODO establish baseIRI
                // 3.2.1)
                $context = IRI::resolveAbsoluteIri($activeContext->getBaseIri(), $context);
                // 3.2.2)
                if (is_array($remoteContexts) && array_search($context, $remoteContexts) !== false) {
                    throw new \Exception('Recursive context inclusion for ' + $context);
                } else {
                    // setting context IRI is not part of the JSON-LD process context algorithm
                    $result->setContextIri($context);
                    $remoteContexts[] = $context;
                }
                // 3.2.3)
                if (array_key_exists($context, $this->dereferencedContexts)) {
                    $context = $this->dereferencedContexts[$context];
                } else {
                    // 3.2.4)
                    $remoteDocument = $this->loadUnknownContext($context);
                    if ($remoteDocument === false) {
                        throw new \Exception('Loading remote context failed');
                    }
                    try {
                        $context = json_decode($remoteDocument, true);
                    } catch (\Exception $e) {
                        throw new \Exception('Loading remote context failed', null, $e);
                    }
                    if ($context == null || !JsonLdHelper::isDictionary($context) || !array_key_exists(Keywords::CONTEXT, $context)) {
                        throw new \Exception('Invalid remote context');
                    }
                    $context = $context[Keywords::CONTEXT];
                }
                // 3.2.5)
                $result = $this->processContext($context, $result, $remoteContexts);
                // 3.2.6)
                continue;
            }
            // 3.3)
            if (!JsonLdHelper::isDictionary($context)) {
                throw new \Exception('Invalid local context: Resulting context is not a dictionary');
            }
            // 3.4)
            if (array_key_exists(Keywords::BASE, $context) && count($remoteContexts) === 0) {
                // 3.4.1)
                $value = $context[Keywords::BASE];
                // 3.4.2)
                if ($value == null) {
                    $result->setBaseIri(null);
                } elseif (IRI::isAbsoluteIri($value)) {
                    // 3.4.3)
                    $result->setBaseIri($value);
                } elseif (IRI::isRelativeIri($uri) && $result->getBaseIri() != null) {
                    // 3.4.4)
                    $result->setBaseIri(IRI::resolveAbsoluteIri($result->getBaseIri(), $value));
                } else {
                    // 3.4.5)
                    throw new \Exception("invalid base IRI");
                }
            }
            // 3.5)
            if (array_key_exists(Keywords::VERSION, $context)) {
                // 3.5.1)
                if (!$context[Keywords::VERSION] == self::VERSION_1_1) {
                    throw new \Exception("invalid @version value");
                }
                // 3.5.2)
                if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                    throw new \Exception("processing mode conflict");
                }
                // 3.5.3)
                $this->processingMode == self::PROCESSING_MODE_JSON_LD_1_1;
            }
            // 3.6)
            if (array_key_exists(Keywords::VOCAB, $context)) {
                // 3.6.1)
                $value = $context[Keywords::VOCAB];
                // 3.6.2)
                if ($value == null) {
                    $result->setVocabularyMapping(null);
                }
                // 3.6.3)
                if ($value == "") {
                    // TODO Confirm that this is intended by 'Otherwise, if value the empty string (""), the effective value is the current base IRI.'
                    $result->setVocabularyMapping($result->getBaseIri());
                } elseif (IRI::isAbsoluteIri($value) || self::isBlankNodeIdentifier($value)) {
                    // 3.6.4)
                    $result->setVocabularyMapping($value);
                } else {
                    throw new \Exception("invalid vocab mapping");
                }
            }
            // 3.7)
            if (array_key_exists(Keywords::LANGUAGE, $context)) {
                // 3.7.1)
                $value = $context->get(Keywords::LANGUAGE);
                // 3.7.2)
                if ($value == null) {
                    $result->setDefaultLanguage(null);
                } elseif (is_string($value)) {
                    // 3.7.3)
                    $result->setDefaultLanguage(strtolower($value));
                } else {
                    throw new \Exception("invalid default language");
                }
            }
            // 3.8)
            $defined = array();
            // 3.9)
            foreach ($context as $key => $v) {
                if (array_search($key, [
                    Keywords::BASE,
                    Keywords::VOCAB,
                    Keywords::LANGUAGE,
                    Keywords::VERSION
                ]) === false) {
                    $this->createTermDefinition($result, $context, $key, $defined);
                }
            }
        }
        // 4)
        return $result;
    }

    protected function createTermDefinition(JsonLdContext $activeContext, $localContext, $term, &$defined) {
        // Numbering as in draft https://w3c.github.io/json-ld-api/#algorithm-0 on 2019-01-10 
        // 1)
        if (array_key_exists($term, $defined)) {
            if ($defined[$term] === true) {
                return;
            } else {
                throw new \Exception("cyclic IRI mapping");
            }
        }
        // 2)
        $defined[$term] = false;
        // 3
        $value = $localContext[$term];
        //4
        if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_1 && $term == Keywords::TYPE &&
            (!JsonLdHelper::isDictionary($value) || $value != [Keywords::CONTAINER => Keywords::SET])) {
            throw new \Exception("keyword redefinition");
        }
        // 5
        if (Keywords::isKeyword($term)) {
            throw new \Exception("keyword redefinition");
        }
        // 6
        $activeContext->removeTermDefinition($term);
        // 7
        if ($value == null || (JsonLdHelper::isDictionary($value) && array_key_exists(Keywords::ID, $value) && $value[Keywords::ID] == null)) {
            $defined[$term] = true;
            return;
        }
        // 8
        if (is_string($value)) {
            $value = [
                "@id" => $value
            ];
            $simpleTerm = true;
        } else {
            // 9
            if (! JsonLdHelper::isDictionary($value)) {
                throw new \Exception("invalid term definition");
            }
            $simpleTerm = false;
        }
        // 10
        $definition = new TermDefinition();
        // 11
        if (array_key_exists(Keywords::TYPE, $value)) {
            // 11.1
            $type = $value[Keywords::TYPE];
            if (! is_string($type)) {
                throw new \Exception("invalid type mapping");
            }
            // 11.2
            $type = $this->expandIRI($activeContext, $type, false, true, $localContext, $defined);
            if ($type != Keywords::ID && $type != Keywords::VOCAB && ! IRI::isAbsoluteIri($type)) {
                throw new \Exception("invalid type mapping");
            }
            // 11.3
            $definition->setTypeMapping($type);
        }
        // 12
        if (array_key_exists(Keywords::REVERSE, $value)) {
            // 12.1
            if (array_key_exists(Keywords::ID, $value) || array_key_exists(Keywords::NEST, $value)) {
                throw new \Exception("invalid reverse property");
            }
            // 12.2
            if (! is_string($value[Keywords::REVERSE])) {
                throw new \Exception("invalid IRI mapping");
            }
            // 12.3
            $expanededIri = $this->expandIRI($activeContext, $value[Keywords::REVERSE], false, true, $localContext, $defined);
            if (strpos($expanededIri, ":") === false) {
                throw new \Exception("invalid IRI mapping");
            }
            $definition->setIriMapping($expandedIri);
            // 12.4
            if (array_key_exists(Keywords::CONTAINER, $value)) {
                $containerMapping = $value[Keywords::CONTAINER];
                if ($containerMapping != null && $containerMapping != Keywords::INDEX && $containerMapping != Keywords::SET) {
                    throw new \Exception("invalid reverse property");
                }
                $definition->setContainerMapping($containerMapping);
            }
            // 12.5
            $definition->setReverse(true);
            // 12.6
            $activeContext->addTermDefinition($term, $definition);
            $defined[$term] = true;
            return;
        }
        // 13
        $definition->setReverse(false);
        // 14
        if (array_key_exists(Keywords::ID, $value) && $value[Keywords::ID] != $term) {
            // 14.1
            if (!is_string($value[Keywords::ID])) {
                throw new \Exception("invalid IRI mapping");
            }
            // 14.2
            $expanededIri = $this->expandIRI($activeContext, $value[Keywords::ID], false, true, $localContext, $defined);
            if (Keywords::isKeyword($expanededIri)) {
                // Keeping track of keyword aliases - not part of the JSON-LD context processing algorithm
                $activeContext->addKeywordAlias($expanededIri, $term);
            }
            if (!Keywords::isKeyword($expanededIri) && !IRI::isAbsoluteIri($expanededIri) && !self::isBlankNodeIdentifier($expanededIri)) {
                throw new \Exception("invalid IRI mapping");
            }
            if ($expanededIri == Keywords::CONTEXT) {
                throw new \Exception("invalid keyword alias");
            }
            $definition->setIriMapping($expanededIri);
            // 14.3
            if (strpos($term, ":") === false && $simpleTerm && array_search(mb_substr($definition->getIriMapping(), count($definition->getIriMapping()) - 1, 1, 'utf-8'), self::GEN_DELIM) !== false) {
                $definition->setPrefix(true);
            }
        } elseif (strpos($term, ":") !== false) {
            // 15
            $prefix = explode(":", $term)[0];
            $suffix = explode(":", $term, 2)[1];
            // 15.1
            if (IRI::isCompactUri($term) && array_key_exists($prefix, $localContext)) {
                $this->createTermDefinition($activeContext, $localContext, $prefix, $defined);
            }
            // 15.2
            if (($prefixDefinition = $activeContext->getTermDefinition($prefix)) != null) {
                $definition->setIriMapping($prefixDefinition->getIriMapping() . $suffix);
            } else {
                // 15.3
                $definition->setIriMapping($term);
            }
        } elseif ($term == Keywords::TYPE) {
            // 16
            $definition->setIriMapping(Keywords::TYPE);
        } elseif ($activeContext->getVocabularyMapping() != null) {
            // 17
            $definition->setIriMapping($activeContext->getVocabularyMapping() . $term);
        } else {
            throw new \Exception("invalid IRI mapping");
        }
        // 18
        if (array_key_exists(Keywords::CONTAINER, $value)) {
            // 18.1
            $allowedInContainer = [
                Keywords::GRAPH,
                Keywords::ID,
                Keywords::INDEX,
                Keywords::LANGUAGE,
                Keywords::LIST_,
                Keywords::SET,
                Keywords::TYPE
            ];
            $container = $value[Keywords::CONTAINER];
            /*
             * container is string:
             * - allowed keyword
             * container is array
             * - consisting of exactly one of those allowed keywords
             * - @graph and either @id or @index, optionally @set
             * - @set and any of @index, @id, @type, @language
             */
            $valid = false;
            if (is_string($container) && array_search($container, $allowedInContainer) !== false) {
                $valid = true;
            } elseif (is_array($container)) {
                if (count($container) == 1 && array_search($container[0], $allowedInContainer) !== false) {
                    $valid == true;
                } elseif (array_search(Keywords::GRAPH, $container) !== false) {
                    $valid = (count($container) == 2 && (array_search(Keywords::ID, $container) !== false || array_search(Keywords::INDEX, $container) !== false)) || (count($container) == 3 && (array_search(Keywords::ID, $container) !== false || array_search(Keywords::INDEX, $container) !== false) && array_search(Keywords::SET, $container) !== false);
                } elseif (array_search(Keywords::SET, $container) !== false) {
                    $valid = true;
                    $allowedWithSet = [
                        Keywords::INDEX,
                        Keywords::ID,
                        Keywords::TYPE,
                        Keywords::LANGUAGE,
                        Keywords::SET
                    ];
                    foreach ($container as $containerMember) {
                        if (! array_search($containerMember, $allowedWithSet) !== false) {
                            $value = false;
                            break;
                        }
                    }
                }
            }
            if (! $valid) {
                throw new \Exception("invalid container mapping");
            }
            // 18.2
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0 && ($container == Keywords::GRAPH || $container == Keywords::ID || $container == Keywords::TYPE || ! is_string($container))) {
                throw new \Exception("invalid container mapping");
            }
            // 18.3
            $definition->setContainerMapping($container);
        }
        // 19
        if (array_key_exists(Keywords::CONTEXT, $value)) {
            // 19.1
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                throw new \Exception("invalid term definition");
            }
            // 19.2
            $context = $value[Keywords::CONTEXT];
            // 19.3
            try {
                $this->processContext($context, $activeContext);
            } catch (\Exception $e) {
                throw new \Exception("invalid scoped context", null, $e);
            }
            // 19.4
            $definition->setLocalContext($context);
        }
        // 20
        if (array_key_exists(Keywords::LANGUAGE, $value) && ! array_key_exists(Keywords::TYPE, $value)) {
            // 20.1
            $language = $value[Keywords::LANGUAGE];
            if (! $language == null && ! is_string($language)) {
                throw new \Exception("invalid language mapping");
            }
            // 20.2
            if (is_string($language)) {
                $language = strtolower($language);
            }
            $definition->setLanguageMapping($lanuage);
        }
        // 21
        if (array_key_exists(Keywords::NEST, $value)) {
            // 21.1
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                throw new \Exception("invalid term definition");
            }
            // 21.2
            if (!is_string($value[Keywords::NEST]) || ($value[Keywords::NEST] != Keywords::NEST && Keywords::isKeyword($value[Keywords::NEST]))) {
                throw new \Exception("invalid @nest value");
            }
            $definition->setNestValue($nestValue);
        }
        // 22
        if (array_key_exists(Keywords::PREFIX, $value)) {
            // 22.1
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0 || strpos($term, ":") !== false) {
                throw new \Exception("invalid term definition");
            }
            // 22.2
            $prefix = $value[Keywords::PREFIX];
            if (! is_bool($prefix)) {
                throw new \Exception("invalid @prefix value");
            }
            $definition->setPrefix($prefix);
        }
        // 23
        $allowedKeysInValue = [
            Keywords::ID,
            Keywords::REVERSE,
            Keywords::CONTAINER,
            Keywords::CONTEXT,
            Keywords::NEST,
            Keywords::PREFIX,
            Keywords::TYPE,
            Keywords::LANGUAGE
        ];
        foreach ($value as $key => $v) {
            if (array_search($key, $allowedKeysInValue) === false) {
                throw new \Exception("invalid term definition");
            }
        }
        // 24
        $activeContext->addTermDefinition($term, $definition);
        $defined[$term] = true;
    }

    /**
     * - transform IRI string into absolute IRIs or blank node identifiers
     * - transform json-ld keyword aliases into keywords
     *
     * @param JsonLdContext $activeContext
     * @param string $value
     * @param boolean $documentRelative
     * @param boolean $vocab
     * @param array $localContext
     * @param array $defined
     * @return mixed
     */
    public function expandIRI(JsonLdContext $activeContext, $value, $documentRelative = false, $vocab = false, $localContext = null, $defined = null) {
        // Numbering as in draft https://w3c.github.io/json-ld-api/#algorithm-1 on 2019-01-10
        // 1)
        if ($value == null || Keywords::isKeyword($value)) {
            return $value;
        }
        // 2)
        if ($localContext != null && array_key_exists($value, $localContext) && $defined[$value] !== true) {
            $this->createTermDefinition($activeContext, $localContext, $value, $defined);
        }
        // 3)
        if (($possibleKeyword = $activeContext->getTermDefinition($value)) != null && Keywords::isKeyword($possibleKeyword->getIriMapping())) {
            return $possibleKeyword->getIriMapping();
        }
        // 4)
        if ($vocab && ($termDefinition = $activeContext->getTermDefinition($value)) != null) {
            return $termDefinition->getIriMapping();
        }
        // 5)
        if (strpos($value, ":") !== false) {
            // 5.1
            $splitValue = explode(":", $value, 2);
            $prefix = $splitValue[0];
            $suffix = $splitValue[1];
            // 5.2
            if ($prefix == "_" || strpos($suffix, "//") === 0) {
                return $value;
            }
            // 5.3
            if ($localContext != null && array_key_exists($prefix, $localContext) && $defined[$prefix] !== true) {
                $this->createTermDefinition($activeContext, $localContext, $prefix, $defined);
            }
            // 5.4
            if (($termDefinition = $activeContext->getTermDefinition($prefix)) != null) {
                return $termDefinition->getIriMapping() . $suffix;
            }
            // 5.5
            if (IRI::isAbsoluteIri($value)) {
                return $value;
            }
        }
        // 6)
        if ($vocab && $activeContext->getVocabularyMapping() != null) {
            return $activeContext->getVocabularyMapping() . $value;
        } elseif ($documentRelative) {
            // 7)
            $value = IRI::resolveAbsoluteIri($activeContext->getBaseIri(), $value);
        }
        // 8)
        return $value;
    }

    public static function isBlankNodeIdentifier($term) {
        return strpos($term, "_:") === 0;
    }
}
