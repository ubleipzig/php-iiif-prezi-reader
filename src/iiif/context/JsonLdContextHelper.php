<?php
namespace iiif\context;

include 'JsonLdContext.php';
include 'JsonLdPrefix.php';
include 'Keywords.php';
include 'Term.php';
include 'IRI.php';

class JsonLdContextHelper
{
    public static function loadJsonLdContext($contextUrl)
    {
        $contextJson = file_get_contents($contextUrl);
        $contextJsonArray = json_decode($contextJson, true);
        if (!array_key_exists(Keywords::BASE, $contextJsonArray)) {
            $contextJsonArray[Keywords::CONTEXT][Keywords::BASE] = $contextUrl;
        }
        return self::analyzeTerm(Keywords::CONTEXT, $contextJsonArray[Keywords::CONTEXT]);
    }
    
    protected static function analyzeTerm($term, $definition, JsonLdContext &$callingContext = null) {
        if (Keywords::isKeyword($term)) {
            switch ($term) {
                case Keywords::CONTEXT:
                    //if (array_key_exists(Keywords::BASE, $definition)
                    $context = new JsonLdContext();
                    $contextJsonAsArray = null; 
                    if (is_array($definition)) {
                        // Context is a list of contexts 
                        if (self::isSequentialArray($definition)) {
                            foreach ($definition as $contextIndex => $contextDefinition) {
                                $context->addContext(self::analyzeTerm(Keywords::CONTEXT, $contextDefinition, $context));
                            }
                        } else {
                            // Context is an actual context definition - 
                            $contextJsonAsArray = $definition;
                        }
                    } else {
                        if (array_key_exists($definition, $callingContext->getContexts())) {
                            return $callingContext->getContexts()[$definition];
                        }
                        $contextStringContent = file_get_contents($definition);
                        $contextJsonAsArray = json_decode($contextStringContent, true);
                        $contextJsonAsArray[Keywords::CONTEXT][Keywords::BASE] = $definition;
                    }
                    if ($contextJsonAsArray == null) return null;
                    if (array_key_exists(Keywords::CONTEXT, $contextJsonAsArray) && sizeof($contextJsonAsArray)===1) {
                        return self::analyzeTerm(Keywords::CONTEXT, $contextJsonAsArray[Keywords::CONTEXT], $callingContext);
                    }
                    foreach ($contextJsonAsArray as $contextTerm => $contextDefinition) {
                        self::analyzeTerm($contextTerm, $contextDefinition, $context);
                    }
                    return $context;
                    break;
                case Keywords::BASE:
                    $callingContext->setBaseIri($definition);
                    break;
                case Keywords::VERSION:
                    
                    break;
                case Keywords::ID:
                    //$callingContext
                    break;
                default:
                    break;
            }
        } else {
            if (self::isSequentialArray($definition)) {
                
            } elseif (is_array($definition)) {
                if (array_key_exists(Keywords::ID, $definition)) {
                    $id = $definition[Keywords::ID];
                    $collectionContainer = null;
                    $language = false;
                    $type = null;
                    $context = null;
                    if (array_key_exists(Keywords::CONTAINER, $definition)) {
                        $containers = array();
                        if (is_array($definition[Keywords::CONTAINER])) {
                            $containers = array_merge($containers, $definition[Keywords::CONTAINER]);
                        } elseif (is_string($definition[Keywords::CONTAINER])) {
                            $containers[] = $definition[Keywords::CONTAINER];
                        } else {
                            // should not happen, but '"@container": null' can be ignored
                        }
                        foreach($containers as $container) {
                            switch ($container) {
                                case Keywords::LANGUAGE:
                                    $language = true;
                                    break;
                                case Keywords::SET:
                                case Keywords::LIST:
                                    $collectionContainer = $container;
                                    break;
                                default:
                                    break;
                            }
                        }
                        
                    }
                    if (array_key_exists(Keywords::TYPE, $definition)) {
                        $type = $definition[Keywords::TYPE];
                    }
                    if (array_key_exists(Keywords::CONTEXT, $definition)) {
                        $context = self::analyzeTerm(Keywords::CONTEXT, $definition[Keywords::CONTEXT], $callingContext);
                    }
                    $propertyTerm = new Term($term, $id, $callingContext->expandIRI($id), $type, $language, $collectionContainer, $context);
                    $callingContext->addPropertyTerm($propertyTerm);
                }
                
            } elseif (is_string($definition)) {
                if (IRI::isUri($definition) && !IRI::isCompressedUri($definition)) {
                    $callingContext->addPrefix(new JsonLdPrefix($term, $definition));
                }
                $callingContext->addVocabularyEntry(new Term($term, $definition, $callingContext->expandIRI($definition), null, false, null, null));
                
            } else {
                // Scheise was ist pasirt
            }
        }
        
    }
    
    public static function isSequentialArray($array) {
        if (!is_array($array)) {
            return false;
        }
        $lastIndex = sizeof($array) - 1;
        foreach ($array as $key => $value) {
            if (!is_int($key) || $key < 0 || $key > $lastIndex) {
                return false;
            }
        }
        return true;
    }
    
    public static function isDictionary($dictionary) {
        if ($dictionary == null || !is_array($dictionary)) return false;
        foreach ($dictionary as $key => $value) {
            if (!is_string($key)) return false;
            // key is a string, value is a string or an array
        }
        return true;
    }
    
    public static function parseContext($localContext, JsonLdContext $activeContext, $remoteContexts) {
        $result = $activeContext->clone();
        if (!self::isSequentialArray($localContext)) {
            $localContext = [$localContext];
        }
        foreach ($localContext as $context) {
            if ($context == null) {
                continue;
            }
            if (is_string($context)) {
                // // TODO $context = resolveIri()
                if (is_array($remoteContexts) && array_search($context, $remoteContexts)!==false) {
                    throw new \Exception('Recursive context inclusion for '+$context);
                }
                $remoteDocument = file_get_contents($context);
                if ($remoteDocument===false) {
                    throw new \Exception('Loading remote context failed');
                }
                $context = json_decode($remoteDocument, true);
                if ($context == null || !self::isDictionary($context) || !array_key_exists(Keywords::CONTEXT, $context)) {
                    throw new \Exception('Invalid remote context');
                }
                
                
            }
            if (!self::isDictionary($context)) {
                throw new \Exception('Resulting context is not a dictionary');
            }
        }
        $localContextArray = self::normalizeContext($localContext);
    }
    
    
    
    
    
}
