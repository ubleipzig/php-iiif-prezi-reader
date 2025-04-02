<?php
/*
 * Copyright (C) 2019 Leipzig University Library <info@ub.uni-leipzig.de>
 * 
 * This file is part of the php-iiif-prezi-reader.
 * 
 * php-iiif-prezi-reader is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Ubl\Iiif\Context;

use Ubl\Iiif\Tools\IiifHelper;

/**
 * Implementation of the context processing algorithm of the JSON-LD API version 1.1.
 * Expansion, Compaction and Flattening algorithms are not (yet) implemented.
 * 
 * @author Lutz Helm <helm@ub.uni-leipzig.de>
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
     * http://iiif.io/api/image/1/context.json provides the content of the context. See https://github.com/IIIF/api/issues/1300 .
     *
     * @var array Array with the original URI as key and a replacement URL as value
     */
    const REDIRECTIONS = [
        "http://library.stanford.edu/iiif/image-api/1.1/context.json" => "http://iiif.io/api/image/1/context.json"
    ];

    protected $processingMode;

    protected $dereferencedContexts;

    protected $knownContexts;
    
    protected $contextLimit;

    protected $circularInclusionStack = [];
    
    /**
     * 
     * @param boolean $requestknownContexts IIIF Presentation and Image API contexts and annotation contexts will only be
     * requested from remote if true. 
     * @param number $recursionLimit Maximum number of recursive context inclusions before 'context overflow' error is thrown.
     * See https://w3c.github.io/json-ld-api/#dom-jsonlderrorcode-context-overflow
     */
    public function __construct($requestknownContexts = false, $recursionLimit = 100) {
        $this->dereferencedContexts = array();
        $this->knownContexts = array();
        $this->contextLimit = $recursionLimit;
        if (! $requestknownContexts) {
            $this->knownContexts["http://www.w3.org/ns/anno.jsonld"] = __DIR__ . "/../../../resources/contexts/annotation/annotation-context.json";
            $this->knownContexts["http://www.shared-canvas.org/ns/context.json"] = __DIR__ . "/../../../resources/contexts/iiif/presentation-context-1.json";
            $this->knownContexts["http://iiif.io/api/presentation/1/context.json"] = __DIR__ . "/../../../resources/contexts/iiif/presentation-context-1.json";
            $this->knownContexts["http://iiif.io/api/presentation/2/context.json"] = __DIR__ . "/../../../resources/contexts/iiif/presentation-context-2.json";
            $this->knownContexts["http://iiif.io/api/presentation/3/context.json"] = __DIR__ . "/../../../resources/contexts/iiif/presentation-context-3.json";
            $this->knownContexts["http://iiif.io/api/image/1/context.json"] = __DIR__ . "/../../../resources/contexts/iiif/image-context-1.json";
            $this->knownContexts["http://iiif.io/api/image/2/context.json"] = __DIR__ . "/../../../resources/contexts/iiif/image-context-2.json";
            $this->knownContexts["http://iiif.io/api/image/3/context.json"] = __DIR__ . "/../../../resources/contexts/iiif/image-context-3.json";
            $this->knownContexts["http://iiif.io/api/search/1/context.json"] = __DIR__ . "/../../../resources/contexts/iiif/search-context-1.json";
            $this->knownContexts["http://iiif.io/api/auth/1/context.json"] = __DIR__ . "/../../../resources/contexts/iiif/auth-context-1.json";
        }
    }

    private function loadUnknownContext($context) {
        if (array_key_exists($context, self::REDIRECTIONS)) {
            $context = self::REDIRECTIONS[$context];
        }
        if (array_key_exists($context, $this->knownContexts)) {
            return file_get_contents($this->knownContexts[$context]);
        }
        if (strpos($context, "http://example.org") === 0) {
            // only for testing purposes
            return '{"@context":{}}';
        }
        return IiifHelper::getRemoteContent($context);
    }

    /**
     * 
     * @param string|array $localContext
     * @param JsonLdContext $activeContext
     * @param array $remoteContexts
     * @param boolean $overrideProtected
     * @param boolean $propagate
     * @throws \Exception
     * @return \Ubl\Iiif\Context\JsonLdContext
     * @link https://w3c.github.io/json-ld-api/#context-processing-algorithm
     */
    public function processContext($localContext, JsonLdContext $activeContext, $remoteContexts = array(), $overrideProtected = false, $propagate = true) {
        // Numbering as in draft https://w3c.github.io/json-ld-api/#algorithm on 2019-07-17
        // 1)
        $result = clone $activeContext;
        // 2)
        if (JsonLdHelper::isDictionary($localContext) && array_key_exists(Keywords::PROPAGATE, $localContext) !== false) {
            $propagate = $localContext[Keywords::PROPAGATE];
        }
        // 3)
        if ($propagate === false && $result->getPrevious() == null) {
            $result->setPrevious($activeContext);
        }
        // 4)
        if (!JsonLdHelper::isSimpleArray($localContext)) {
            $localContext = [
                $localContext
            ];
        }
        // 5)
        foreach ($localContext as $context) {
            // 5.1)
            if ($context == null) {
                // 5.1.1)
                if (!$overrideProtected && $activeContext->containsProtectedTermDefinitions()) {
                    throw new \Exception('Invalid context nullification');
                }
                // 5.1.2)
                $previousResult = $result;
                $result = new JsonLdContext($this);
                if (!$propagate) {
                    $result->setPrevious($previousResult);
                }
                // TODO handle base iri
                continue;
            }
            // 5.2)
            if (is_string($context)) {
                // TODO establish baseIRI
                // 5.2.1)
                $context = IRI::resolveAbsoluteIri($activeContext->getBaseIri(), $context);
                // 5.2.2)
                if (is_array($remoteContexts) && sizeof($remoteContexts) > $this->contextLimit) {
                    throw new \Exception('Context overflow before adding ' + $context);
                } else {
                    // setting context IRI is not part of the JSON-LD process context algorithm
                    $result->setContextIri($context);
                    $remoteContexts[] = $context;
                }
                // 5.2.3)
                if (array_key_exists($context, $this->dereferencedContexts)) {
                    $context = $this->dereferencedContexts[$context];
                } else {
                    // 5.2.4)
                    $remoteDocument = $this->loadUnknownContext($context);
                    // 5.2.5
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
                // FIXME remove if
                if ($this->getCircularInclusionDepth($result->getContextIri()) > 2) {
                    continue;
                }
                array_push($this->circularInclusionStack, $result->getContextIri()); // FIXME remove
                // 5.2.6)
                $result = $this->processContext($context, $result, $remoteContexts);
                array_pop($this->circularInclusionStack); // FIXME remove
                // 5.2.7)
                continue;
            }
            // 5.3)
            if (!JsonLdHelper::isDictionary($context)) {
                throw new \Exception('Invalid local context: Resulting context is not a dictionary');
            }
            // 5.4)
            // 5.5)
            if (array_key_exists(Keywords::VERSION, $context)) {
                // 5.5.1)
                if (!$context[Keywords::VERSION] == self::VERSION_1_1) {
                    throw new \Exception("invalid @version value");
                }
                // 5.5.2)
                if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                    throw new \Exception("processing mode conflict");
                }
                // 5.5.3)
                $this->processingMode = self::PROCESSING_MODE_JSON_LD_1_1;
            }
            // 5.6)
            if (array_key_exists(Keywords::IMPORT, $context)) {
                // 5.6.1)
                if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                    throw new \Exception('Invalid context entry');
                }
                // 5.6.2)
                if (!is_string($context[Keywords::IMPORT])) {
                    throw new \Exception('Imvalid @import value');
                }
                // 5.6.3)
                $import = IRI::resolveAbsoluteIri($activeContext->getBaseIri(), $context[Keywords::IMPORT]);
                // 5.6.4)
                $importContext = IiifHelper::getRemoteContent($import);
                // 5.6.5)
                if (!is_string($importContext)) {
                    throw new \Exception('Loading remote context failed');
                }
                $importContext = json_decode($importContext, true);
                if ($importContext == null) {
                    throw new \Exception('Loading remote context failed');
                }
                // 5.6.6)
                if (!JsonLdHelper::isDictionary($importContext) || !array_key_exists(Keywords::CONTEXT, $importContext) || !JsonLdHelper::isDictionary($importContext[Keywords::CONTEXT])) {
                    throw new \ErrorException('Invalid remote context');
                }
                // 5.6.7)
                if (array_key_exists(Keywords::IMPORT, $importContext)) {
                    throw new \Exception('Invalid context entry');
                }
                // 5.6.8)
                $context = array_merge($importContext, $context);
            }
            // 5.7)
            if (array_key_exists(Keywords::BASE, $context) && count($remoteContexts) === 0) {
                // 5.7.1)
                $value = $context[Keywords::BASE];
                // 5.7.2)
                if ($value == null) {
                    $result->setBaseIri(null);
                } elseif (IRI::isAbsoluteIri($value)) {
                    // 5.7.3)
                    $result->setBaseIri($value);
                } elseif (IRI::isRelativeIri($uri) && $result->getBaseIri() != null) {
                    // 5.7.4)
                    $result->setBaseIri(IRI::resolveAbsoluteIri($result->getBaseIri(), $value));
                } else {
                    // 5.7.5)
                    throw new \Exception("invalid base IRI");
                }
            }
            // 5.8)
            if (array_key_exists(Keywords::VOCAB, $context)) {
                // 5.8.1)
                $value = $context[Keywords::VOCAB];
                // 5.8.2)
                if ($value == null) {
                    $result->setVocabularyMapping(null);
                }
                elseif (IRI::isAbsoluteIri($value) || self::isBlankNodeIdentifier($value)) {
                    // 5.8.3)
                    $result->setVocabularyMapping($this->expandIRI($result, $value, true, true));
                } else {
                    throw new \Exception("invalid vocab mapping");
                }
            }
            // 5.9)
            if (array_key_exists(Keywords::LANGUAGE, $context)) {
                // 5.9.1)
                $value = $context->get(Keywords::LANGUAGE);
                // 5.9.2)
                if ($value == null) {
                    $result->setDefaultLanguage(null);
                } elseif (is_string($value)) {
                    // 5.9.3)
                    $result->setDefaultLanguage(strtolower($value));
                } else {
                    throw new \Exception("invalid default language");
                }
            }
            // 5.10)
            if (array_key_exists(Keywords::PROPAGATE, $context)) {
                // 5.10.1)
                if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                    throw new \Exception('invalid context entry');
                }
                // 5.10.2)
                if (($propaValue = $context[Keywords::PROPAGATE]) !== false && $propaValue !== true) {
                    throw new \Exception('invalid @propagate value');
                }
                // 5.10.3 - nothing to do
            }
            // 5.11)
            $defined = array();
            // 3.9)
            $protected_ = array_key_exists(Keywords::PROTECTED_, $context) ? $context[Keywords::PROTECTED_] : false;
            foreach ($context as $key => $v) {
                if (array_search($key, [
                    Keywords::BASE,
                    Keywords::IMPORT,
                    Keywords::LANGUAGE,
                    Keywords::PROPAGATE,
                    Keywords::PROTECTED_,
                    Keywords::VERSION,
                    Keywords::VOCAB
                ]) === false) {
                    $this->createTermDefinition($result, $context, $key, $defined, $protected_);
                }
            }
        }
        // 4)
        return $result;
    }

    /**
     * 
     * @param JsonLdContext $activeContext
     * @param array $localContext
     * @param string $term
     * @param array $defined
     * @throws \Exception
     * @link https://w3c.github.io/json-ld-api/#create-term-definition
     */
    protected function createTermDefinition(JsonLdContext $activeContext, $localContext, $term, &$defined, $protected_ = false, $overrideProtected = false, $propagate = true) {
        // Numbering as in draft https://w3c.github.io/json-ld-api/#algorithm-0 on 2019-04-16 
        // 1)
        if (array_key_exists($term, $defined)) {
            if ($defined[$term] === true) {
                return;
            } elseif ($defined[$term] === false) {
                throw new \Exception("cyclic IRI mapping");
            }
        }
        // 2)
        $defined[$term] = false;
        // 3
        $value = $localContext[$term];
        // 4
        if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_1 && $term == Keywords::TYPE) {
            if (!JsonLdHelper::isDictionary($value) || $value != [Keywords::CONTAINER => Keywords::SET]) {
                throw new \Exception("keyword redefinition");
            }
        } elseif (Keywords::isKeyword($term)) {
            // 5
            throw new \Exception("keyword redefinition");
        }
        // 6
        $previousDefinition = $activeContext->getTermDefinition($term);
//         if ($activeContext->getTermDefinition($term) != null && $activeContext->getTermDefinition($term)->getProtected_()) {
//             return;
//         }
        // 7 TODO check spec
        if (!isset($previousDefinition)) {
            $activeContext->removeTermDefinition($term);
        }
        // 8
        if ($value == null) {
            $value = [
                "@id" => null
            ];
        } elseif (is_string($value)) {
            // 9
            $value = [
                "@id" => $value
            ];
            $simpleTerm = true;
        } else {
            // 10
            if (! JsonLdHelper::isDictionary($value)) {
                throw new \Exception("invalid term definition");
            }
            $simpleTerm = false;
        }
        // 11
        $definition = new TermDefinition($term);
        // 12
        if (array_key_exists(Keywords::PROTECTED_, $value) && $value[Keywords::PROTECTED_] ||
            !array_key_exists(Keywords::PROTECTED_, $value) && $protected_) {
            $definition->setProtected_(true);
        }
        // 13
        if (array_key_exists(Keywords::TYPE, $value)) {
            // 13.1
            $type = $value[Keywords::TYPE];
            if (! is_string($type)) {
                throw new \Exception("invalid type mapping");
            }
            // 13.2
            $type = $this->expandIRI($activeContext, $type, false, true, $localContext, $defined);
            if ($type != Keywords::ID && $type != Keywords::VOCAB &&
                (!($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_1) || $type != Keywords::JSON && $type != Keywords::NONE) &&
                !IRI::isAbsoluteIri($type)) {
                throw new \Exception("invalid type mapping");
            }
            // 13.3
            $definition->setTypeMapping($type);
        }
        // 14
        if (array_key_exists(Keywords::REVERSE, $value)) {
            // 14.1
            if (array_key_exists(Keywords::ID, $value) || array_key_exists(Keywords::NEST, $value)) {
                throw new \Exception("invalid reverse property");
            }
            // 14.2
            if (! is_string($value[Keywords::REVERSE])) {
                throw new \Exception("invalid IRI mapping");
            }
            // 14.3
            $expanededIri = $this->expandIRI($activeContext, $value[Keywords::REVERSE], false, true, $localContext, $defined);
            if (strpos($expanededIri, ":") === false) {
                throw new \Exception("invalid IRI mapping");
            }
            $definition->setIriMapping($expandedIri);
            // 14.4
            if (array_key_exists(Keywords::CONTAINER, $value)) {
                $containerMapping = $value[Keywords::CONTAINER];
                if ($containerMapping != null && $containerMapping != Keywords::INDEX && $containerMapping != Keywords::SET) {
                    throw new \Exception("invalid reverse property");
                }
                $definition->setContainerMapping($containerMapping);
            }
            // 14.5
            $definition->setReverse(true);
            // 14.6
            $activeContext->addTermDefinition($term, $definition);
            $defined[$term] = true;
            return;
        }
        // 15
        $definition->setReverse(false);
        // 16
        if (array_key_exists(Keywords::ID, $value) && $value[Keywords::ID] != $term) {
            // 16.1 TODO
            if ($value[Keywords::ID] == null) {
              // do not expand  
            } else if (!is_string($value[Keywords::ID])) {
                // 16.2
                throw new \Exception("invalid IRI mapping");
            } else {
                // 16.3
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
                // 16.4)
                if (strpos($term, ":") !== false && strpos($term, ":") !== strlen($term) - 1 && $this->expandIRI($activeContext, $term) !== $definition->getIriMapping()) {
                    throw new \Exception("invalid IRI mapping");
                }
                // 16.5)
                if (strpos($term, ":") === false && $simpleTerm && array_search(mb_substr($definition->getIriMapping(), strlen($definition->getIriMapping()) - 1, 1, 'utf-8'), self::GEN_DELIM) !== false) {
                    $definition->setPrefix(true);
                }
            }
        } elseif (strpos($term, ":") !== false) {
            // 17
            $prefix = explode(":", $term)[0];
            $suffix = explode(":", $term, 2)[1];
            // 17.1
            if (preg_match(IRI::COMPACT_IRI_REGEX, $term) && array_key_exists($prefix, $localContext)) {
                $this->createTermDefinition($activeContext, $localContext, $prefix, $defined);
            }
            // 17.2
            if (($prefixDefinition = $activeContext->getTermDefinition($prefix)) != null) {
                $definition->setIriMapping($prefixDefinition->getIriMapping() . $suffix);
            } else {
                // 17.3
                $definition->setIriMapping($term);
            }
        } elseif ($term == Keywords::TYPE) {
            // 18
            $definition->setIriMapping(Keywords::TYPE);
        } elseif ($activeContext->getVocabularyMapping() != null) {
            // 19
            $definition->setIriMapping($activeContext->getVocabularyMapping() . $term);
        } else {
            throw new \Exception("invalid IRI mapping");
        }
        // 20
        if (array_key_exists(Keywords::CONTAINER, $value)) {
            // 20.1
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
            // 20.2
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0 && ($container == Keywords::GRAPH || $container == Keywords::ID || $container == Keywords::TYPE || ! is_string($container))) {
                throw new \Exception("invalid container mapping");
            }
            // 20.3
            $definition->setContainerMapping($container);
        }
        // 21
        if (array_key_exists(Keywords::INDEX, $value)) {
            // 21.1
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0 ||
                ((is_string($definition->getContainerMapping()) && $definition->getContainerMapping() != Keywords::INDEX) ||
                (is_array($definition->getContainerMapping()) && array_search(Keywords::INDEX, $definition->getContainerMapping()) === false))) {
                throw new \Exception("invalid termdefinition");
            }
            // 21.2
            $index = $value[Keywords::INDEX];
            if (!is_string($index) || !IRI::isAbsoluteIri($this->expandIRI($activeContext, $index))) {
                throw new \Exception("invalid term definition");
            }
            // 21.3
            $definition->setIndexMapping($index);
        }
        // 22
        if (array_key_exists(Keywords::CONTEXT, $value)) {
            // 22.1
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                throw new \Exception("invalid term definition");
            }
            // 22.2
            $context = $value[Keywords::CONTEXT];
            // 22.3
            try {
                $this->processContext($context, $activeContext, [], true);
            } catch (\Exception $e) {
                throw new \Exception("invalid scoped context", null, $e);
            }
            // 22.4
            $definition->setLocalContext($context);
        }
        // 23
        if (array_key_exists(Keywords::LANGUAGE, $value) && ! array_key_exists(Keywords::TYPE, $value)) {
            // 23.1
            $language = $value[Keywords::LANGUAGE];
            if ($language != null && ! is_string($language)) {
                throw new \Exception("invalid language mapping");
            }
            // 23.2
            if (is_string($language)) {
                $language = strtolower($language);
            }
            $definition->setLanguageMapping($language);
        }
        // 24
        if (array_key_exists(Keywords::NEST, $value)) {
            // 24.1
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0) {
                throw new \Exception("invalid term definition");
            }
            // 24.2
            $nestValue = $value[Keywords::NEST];
            if (!is_string($nestValue) || ($nestValue != Keywords::NEST && Keywords::isKeyword($nestValue))) {
                throw new \Exception("invalid @nest value");
            }
            $definition->setNestValue($nestValue);
        }
        // 25
        if (array_key_exists(Keywords::PREFIX, $value)) {
            // 25.1
            if ($this->processingMode == self::PROCESSING_MODE_JSON_LD_1_0 || strpos($term, ":") !== false) {
                throw new \Exception("invalid term definition");
            }
            // 25.2
            $prefix = $value[Keywords::PREFIX];
            if (! is_bool($prefix)) {
                throw new \Exception("invalid @prefix value");
            }
            $definition->setPrefix($prefix);
        }
        // 26
        $allowedKeysInValue = [
            Keywords::ID,
            Keywords::REVERSE,
            Keywords::CONTAINER,
            Keywords::CONTEXT,
            Keywords::LANGUAGE,
            Keywords::NEST,
            Keywords::PREFIX,
            Keywords::TYPE
        ];
        foreach ($value as $key => $v) {
            if (array_search($key, $allowedKeysInValue) === false) {
                throw new \Exception("invalid term definition");
            }
        }
        // 29
        if ($overrideProtected === false && $previousDefinition != null && $previousDefinition->getProtected_() === true) {
            if ($definition != $previousDefinition) {
                throw new \Exception("protected term redefinition");
            }
            $definition = $previousDefinition;
        }
        // 28
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
     * @link https://w3c.github.io/json-ld-api/#iri-expansion
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
            if (($termDefinition = $activeContext->getTermDefinition($prefix)) != null && $termDefinition->getPrefix() && $termDefinition->getIriMapping() !== null) {
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

    private function getCircularInclusionDepth($contextUrl) {
        // FIXME this is only needed for a workaround. Figure out how to handle circular inclusion once json-ld 1.1 is implementation ready.
        $count = 0;
        foreach ($this->circularInclusionStack as $item) {
            if ($item == $contextUrl) {
                $count++;
            }
        }
        return $count;
    }
}
