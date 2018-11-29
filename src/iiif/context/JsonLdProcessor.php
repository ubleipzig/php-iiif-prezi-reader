<?php
namespace iiif\context;

use iiif\tools\RemoteUrlHelper;

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
            $this->knownIiifContexts["http://iiif.io/api/presentation/3/context.json"] = __DIR__ . "/../../../resources/v3/presentation-context-3.json";
            $this->knownIiifContexts["http://www.w3.org/ns/anno.jsonld"] = __DIR__ . "/../../../resources/v3/annotation-context.json";
            $this->knownIiifContexts["http://iiif.io/api/presentation/3/combined-context.json"] = __DIR__ . "/../../../resources/v3/presentation-combined-context-3.json";
            $this->knownIiifContexts["http://iiif.io/api/image/1/context.json"] = __DIR__ . "/../../../resources/v3/image-context-1.json";
            $this->knownIiifContexts["http://iiif.io/api/image/2/context.json"] = __DIR__ . "/../../../resources/v3/image-context-2.json";
            $this->knownIiifContexts["http://iiif.io/api/search/1/context.json"] = __DIR__ . "/../../../resources/v3/search-context-1.json";
            $this->knownIiifContexts["http://iiif.io/api/auth/1/context.json"] = __DIR__ . "/../../../resources/v3/auth-context-1.json";
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
        return RemoteUrlHelper::getContent($context);
    }

    public static function isSequentialArray($array) {
        if (! is_array($array)) {
            return false;
        }
        $lastIndex = sizeof($array) - 1;
        foreach ($array as $key => $value) {
            if (! is_int($key) || $key < 0 || $key > $lastIndex) {
                return false;
            }
        }
        return true;
    }

    public static function isDictionary($dictionary) {
        if ($dictionary == null || ! is_array($dictionary))
            return false;
        foreach ($dictionary as $key => $value) {
            if (! is_string($key))
                return false;
            if ($value != null && ! is_scalar($value) && ! is_array($value))
                return false;
        }
        return true;
    }

    public function processContext($localContext, JsonLdContext $activeContext, $remoteContexts = array()) {
        $result = $activeContext->clone();
        if (! self::isSequentialArray($localContext)) {
            $localContext = [
                $localContext
            ];
        }
        foreach ($localContext as $context) {
            if ($context == null) {
                $result = new JsonLdContext();
                // TODO handle base iri
                continue;
            }
            if (is_string($context)) {
                // TODO establish baseIRI
                $context = IRI::resolveAbsoluteIri($activeContext->getBaseIri(), $context);
                if (is_array($remoteContexts) && array_search($context, $remoteContexts) !== false) {
                    throw new \Exception('Recursive context inclusion for ' + $context);
                } else {
                    $result->setContextIri($context);
                    $remoteContexts[] = $context;
                }
                if (array_key_exists($context, $this->dereferencedContexts)) {
                    $context = $this->dereferencedContexts[$context];
                } else {
                    $remoteDocument = $this->loadUnknownContext($context);
                    if ($remoteDocument === false) {
                        throw new \Exception('Loading remote context failed');
                    }
                    try {
                        $context = json_decode($remoteDocument, true);
                    } catch (\Exception $e) {
                        throw new \Exception('Loading remote context failed', null, $e);
                    }
                }
                if ($context == null || ! self::isDictionary($context) || ! array_key_exists(Keywords::CONTEXT, $context)) {
                    throw new \Exception('Invalid remote context');
                }
                $context = $context[Keywords::CONTEXT];
                $result = $this->processContext($context, $result, $remoteContexts);
                continue;
            }
            // 3.3
            if (! self::isDictionary($context)) {
                throw new \Exception('Invalid local context: Resulting context is not a dictionary');
            }
            // 3.4
            if (array_key_exists(Keywords::BASE, $context) && count($remoteContexts) === 0) {
                // 3.4.1
                $value = $context[Keywords::BASE];
                //
                if ($value == null) {
                    $result->setBaseIri(null);
                } elseif (IRI::isAbsoluteIri($value)) {
                    $result->setBaseIri($value);
                } elseif (IRI::isRelativeIri($uri) && $result->getBaseIri() != null) {
                    // TODO resolve $value against current base IRI
                    $result->setBaseIri();
                } else {
                    throw new \Exception("invalid base IRI");
                }
            }
            // 3.5
            if (array_key_exists(Keywords::VERSION, $context)) {
                if (! $context[Keywords::VERSION] == self::VERSION_1_1) {
                    throw new \Exception("invalid @version value");
                }
                if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                    throw new \Exception("processing mode conflict");
                }
                $this->processingMode == self::PROCESSING_MODE_JSON_LD_1_1;
            }
            if (array_key_exists(Keywords::VOCAB, $context)) {
                $value = $context[Keywords::VOCAB];
                if ($value == null) {
                    $result->setVocabularyMapping(null);
                }
                if ($value == "") {
                    $result->setVocabularyMapping($result->getBaseIri());
                } elseif (IRI::isAbsoluteIri($value) || self::isBlankNodeIdentifier($value)) {
                    $result->setVocabularyMapping($value);
                } else {
                    throw new \Exception("invalid vocab mapping");
                }
            }
            if (array_key_exists(Keywords::LANGUAGE, $context)) {
                $value = $context->get(Keywords::LANGUAGE);
                if ($value == null) {
                    $result->setDefaultLanguage(null);
                } elseif (is_string($value)) {
                    $result->setDefaultLanguage(strtolower($value));
                } else {
                    throw new \Exception("invalid default language");
                }
            }
            $defined = array();
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
        return $result;
    }

    protected function createTermDefinition(JsonLdContext $activeContext, $localContext, $term, &$defined) {
        if (array_key_exists($term, $defined)) {
            if ($defined[$term] === true) {
                return;
            } else {
                throw new \Exception("cyclic IRI mapping");
            }
        }
        $defined[$term] = false;
        if (Keywords::isKeyword($term)) {
            throw new \Exception("keyword redefinition");
        }
        $activeContext->removeTermDefinition($term);
        $value = $localContext[$term];
        if ($value == null || (self::isDictionary($value) && array_key_exists(Keywords::ID, $value) && $value[Keywords::ID] == null)) {
            $defined[$term] = true;
            return;
        }
        if (is_string($value)) {
            $value = [
                "@id" => $value
            ];
            $simpleTerm = true;
        } else {
            if (! self::isDictionary($value)) {
                throw new \Exception("invalid term definition");
            }
            $simpleTerm = false;
        }
        $definition = new TermDefinition();
        if (array_key_exists(Keywords::TYPE, $value)) {
            $type = $value[Keywords::TYPE];
            if (! is_string($type)) {
                throw new \Exception("invalid type mapping");
            }
            $type = $this->expandIRI($activeContext, $type, false, true, $localContext, $defined);
            if ($type != Keywords::ID && $type != Keywords::VOCAB && ! IRI::isAbsoluteIri($type)) {
                throw new \Exception("invalid type mapping");
            }
            $definition->setTypeMapping($type);
        }
        if (array_key_exists(Keywords::REVERSE, $value)) {
            if (array_key_exists(Keywords::ID, $value) || array_key_exists(Keywords::NEST, $value)) {
                throw new \Exception("invalid reverse property");
            }
            if (! is_string($value[Keywords::REVERSE])) {
                throw new \Exception("invalid IRI mapping");
            }
            $expanededIri = $this->expandIRI($activeContext, $value);
            if (strpos($expanededIri, ":") === false) {
                throw new \Exception("invalid IRI mapping");
            }
            $definition->setIriMapping($expandedIri);
            if (array_key_exists(Keywords::CONTAINER, $value)) {
                $containerMapping = $value[Keywords::CONTAINER];
                if ($containerMapping != null && $containerMapping != Keywords::INDEX && $containerMapping != Keywords::SET) {
                    throw new \Exception("invalid reverse property");
                }
                $definition->setContainerMapping($containerMapping);
            }
            $definition->setReverse(true);
            $activeContext->addTermDefinition($term, $definition);
            $defined[$term] = true;
            return;
        }
        $definition->setReverse(false);
        if (array_key_exists(Keywords::ID, $value) && $value[Keywords::ID] != $term) {
            if (! is_string($value[Keywords::ID])) {
                throw new \Exception("invalid IRI mapping");
            }
            $expanededIri = $this->expandIRI($activeContext, $value[Keywords::ID], false, true, $localContext, $defined);
            if (Keywords::isKeyword($expanededIri)) {
                // Keeping track of keyword aliases - not part of the JSON-LD context processing algorithm
                $activeContext->addKeywordAlias($expanededIri, $term);
            }
            if (! Keywords::isKeyword($expanededIri) && ! IRI::isAbsoluteIri($expanededIri) && ! self::isBlankNodeIdentifier($expanededIri)) {
                throw new \Exception("invalid IRI mapping");
            }
            if ($expanededIri == Keywords::CONTEXT) {
                throw new \Exception("invalid keyword alias");
            }
            $definition->setIriMapping($expanededIri);
            if (strpos($term, ":") === false && $simpleTerm && array_search(mb_substr($definition->getIriMapping(), count($definition->getIriMapping()) - 1, 1, 'utf-8'), self::GEN_DELIM) !== false) {
                $definition->setPrefix(true);
            }
        } elseif (strpos($term, ":") != false) {
            $prefix = explode(":", $term)[0];
            $suffix = explode(":", $term, 2)[1];
            if (IRI::isCompactUri($term) && array_key_exists($prefix, $localContext)) {
                $this->createTermDefinition($activeContext, $localContext, $prefix, $defined);
            }
            if (($prefixDefinition = $activeContext->getTermDefinition($prefix)) != null) {
                $definition->setIriMapping($prefixDefinition->getIriMapping() . $suffix);
            } else {
                $definition->setIriMapping($term);
            }
        } elseif ($activeContext->getVocabularyMapping() != null) {
            $definition->setIriMapping($activeContext->getVocabularyMapping() . $term);
        } else {
            throw new \Exception("invalid IRI mapping");
        }
        if (array_key_exists(Keywords::CONTAINER, $value)) {
            $allowedInContainer = [
                Keywords::GRAPH,
                Keywords::ID,
                Keywords::INDEX,
                Keywords::LANGUAGE,
                Keywords::LIST,
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
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0 && ($container == Keywords::GRAPH || $container == Keywords::ID || $container == Keywords::TYPE || ! is_string($container))) {
                throw new \Exception("invalid container mapping");
            }
            $definition->setContainerMapping($container);
        }
        if (array_key_exists(Keywords::CONTEXT, $value)) {
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                throw new \Exception("invalid term definition");
            }
            $context = $value[Keywords::CONTEXT];
            try {
                $this->processContext($context, $activeContext);
            } catch (\Exception $e) {
                throw new \Exception("invalid scoped context", null, $e);
            }
            $definition->setContext($context); // FIXME local context, 17.4
        }
        if (array_key_exists(Keywords::LANGUAGE, $value) && ! array_key_exists(Keywords::TYPE, $value)) {
            $language = $value[Keywords::LANGUAGE];
            if (! $language == null && ! is_string($language)) {
                throw new \Exception("invalid language mapping");
            }
            if (is_string($language)) {
                $language = strtolower($language);
            }
            $definition->setLanguageMapping($lanuage);
        }
        if (array_key_exists(Keywords::NEST, $value)) {
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                throw new \Exception("invalid term definition");
            }
            if (! is_string($value[Keywords::NEST]) || ($value[Keywords::NEST] != Keywords::NEST && Keywords::isKeyword($value[Keywords::NEST]))) {
                throw new \Exception("invalid @nest value");
            }
            $definition->setNestValue($nestValue);
        }
        if (array_key_exists(Keywords::PREFIX, $value)) {
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0 || strpos($term, ":") !== false) {
                throw new \Exception("invalid term definition");
            }
            $prefix = $value[Keywords::PREFIX];
            if (! is_bool($prefix)) {
                throw new \Exception("invalid @prefix value");
            }
            $definition->setPrefix($prefix);
        }
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
        if ($value == null || Keywords::isKeyword($value)) {
            return $value;
        }
        if ($localContext != null && array_key_exists($value, $localContext) && $defined[$value] !== true) {
            $this->createTermDefinition($activeContext, $localContext, $value, $defined);
        }
        if (($possibleKeyword = $activeContext->getTermDefinition($value)) != null && Keywords::isKeyword($possibleKeyword->getIriMapping())) {
            return $possibleKeyword->getIriMapping();
        }
        if ($vocab && ($termDefinition = $activeContext->getTermDefinition($value)) != null) {
            return $termDefinition->getIriMapping();
        }
        if (strpos($value, ":") !== false) {
            $splitValue = explode(":", $value, 2);
            $prefix = $splitValue[0];
            $suffix = $splitValue[1];
            if ($prefix == "_" || strpos($suffix, "//") === 0) {
                return $value;
            }
            if ($localContext != null && array_key_exists($prefix, $localContext) && $defined[$prefix] !== true) {
                $this->createTermDefinition($activeContext, $localContext, $prefix, $defined);
            }
            if (($termDefinition = $activeContext->getTermDefinition($prefix)) != null) {
                return $termDefinition->getIriMapping() . $suffix;
            }
            return $value;
        }
        if ($vocab && $activeContext->getVocabularyMapping() != null) {
            return $activeContext->getVocabularyMapping() . $value;
        } elseif ($documentRelative) {
            $value = IRI::resolveAbsoluteIri($activeContext->getBaseIri(), $value);
        }
        return $value;
    }

    public static function isBlankNodeIdentifier($term) {
        return strpos($term, "_:") === 0;
    }
}
