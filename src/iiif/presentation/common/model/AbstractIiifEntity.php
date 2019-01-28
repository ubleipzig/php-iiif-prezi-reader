<?php
namespace iiif\presentation\common\model;

use Flow\JSONPath\JSONPath;
use iiif\context\IRI;
use iiif\context\JsonLdContext;
use iiif\context\JsonLdHelper;
use iiif\context\JsonLdProcessor;
use iiif\context\Keywords;
use iiif\presentation\common\TypeMap;
use iiif\presentation\v2\model\resources\AbstractIiifResource;
use iiif\presentation\v3\model\resources\AbstractIiifResource3;
use iiif\tools\IiifHelper;
use iiif\presentation\common\model\resources\IiifResourceInterface;
use iiif\presentation\v1\model\resources\AbstractIiifResource1;

abstract class AbstractIiifEntity {

    /**
     *
     * @var array
     */
    protected $originalJsonArray;

    /**
     * Only used during intialization / loading of a IIIF document
     *  
     * @var boolean true if the object has been created or updated after evaluating the resource's definition;
     * false if the object for the resource has just been created to represent a reference from another object 
     */
    protected $initialized = false;

    protected $containedResources;

    protected $type;

    public static function loadIiifResource($resource) {
        if (is_string($resource) && IRI::isAbsoluteIri($resource)) {
            $resource = IiifHelper::getRemoteContent($resource);
        }
        if (is_string($resource)) {
            $resource = json_decode($resource, true);
        }
        if (JsonLdHelper::isDictionary($resource)) {
            return self::parseDictionary($resource);
        }
        return null;
    }

    /**
     *
     * @return boolean
     */
    public function isInitialized() {
        return $this->initialized;
    }

    /**
     *
     * @return array
     */
    public function getOriginalJsonArray() {
        return $this->originalJsonArray;
    }

    /**
     * Properties that link to IIIF resources via URI string rather than id:URI/type:type dictionary.
     *
     * @return array
     */
    protected function getStringResources() {
        return [];
    }

    /**
     * Names of properties that resolve to AbstractIiifEntity but have not @type given.
     * Applies to "service" property.
     * Method has to be overridden by affected classes.
     *
     * @return array
     */
    protected function getTypelessProperties() {
        return [];
    }
    
    protected function getSpecialTreatmentValue($property, $value, $context) {
        return $value;
    }
    
    /**
     * Names of properites that may contain values as a sequential array but whose term definition does not 
     * have the suiting @container property. Mostly used for missing @set in Presentation API 2.
     * @return array
     */
    protected function getCollectionProperties() {
        return [];
    }

    protected function getValueForTypelessProperty($property, $dictionary, JsonLdContext $context) {}

    /**
     *
     * @param array $dictionary
     * @param JsonLdContext $context
     * @return AbstractIiifEntity
     */
    protected static function parseDictionary(array $dictionary, JsonLdContext $context = null, &$allResources = array(), $processor = null) {
        $noParent = $context === null;
        if (array_key_exists(Keywords::CONTEXT, $dictionary)) {
            $processor = new JsonLdProcessor();
            $context = $processor->processContext($dictionary[Keywords::CONTEXT], new JsonLdContext($processor));
        }
        $typeOrAlias = $context->getKeywordOrAlias(Keywords::TYPE);
        $idOrAlias = $context->getKeywordOrAlias(Keywords::ID);
        $contextOrAlias = $context->getKeywordOrAlias(Keywords::CONTEXT);
        if (array_key_exists($typeOrAlias, $dictionary) || array_key_exists($contextOrAlias, $dictionary)) {
            $type = array_key_exists($typeOrAlias, $dictionary) ? $dictionary[$typeOrAlias] : $dictionary[$contextOrAlias];
            $id = array_key_exists($idOrAlias, $dictionary) ? $dictionary[$idOrAlias] : null;
            if ($id != null && array_key_exists($id, $allResources)) {
                $resource = $allResources[$id]["resource"];
            } else {
                $typeIri = IRI::isAbsoluteIri($type) ? $type : (IRI::isCompactUri($type) ? $processor->expandIRI($context, $type) : $context->getTermDefinition($type)->getIriMapping());
                $typeClass = TypeMap::getClassForType($typeIri, $context);
                if ($typeClass == null) {
                    return $dictionary;
                }
                $resource = new $typeClass();
            }
            /* @var $resource \AbstractIiifEntity; */
            if (! $resource->initialized || sizeof(array_diff(array_keys($dictionary), [
                $typeOrAlias,
                $idOrAlias
            ])) > 0) {
                $dictionaryOfDictionaries = [];
                foreach ($dictionary as $key => $value) {
                    if ($key == $typeOrAlias) {
                        $resource->type = $value;
                        continue;
                    }
                    if ($key == Keywords::CONTEXT) {
                        continue;
                    }
                    if (! Keywords::isKeyword($key) && $context->getTermDefinition($key) == null) {
                        continue;
                    }
                    if (JsonLdHelper::isDictionary($value)) {
                        // set basic values first
                        $dictionaryOfDictionaries[$key] = $value;
                        continue;
                    }
                    $resource->loadProperty($key, $value, $context, $allResources, $processor);
                    if ($key != $idOrAlias) {
                        $resource->initialized = true;
                    }
                }
                foreach ($dictionaryOfDictionaries as $key => $value) {
                    $resource->loadProperty($key, $value, $context, $allResources, $processor);
                    if ($key != $idOrAlias) {
                        $resource->initialized = true;
                    }
                }
            }
            $resource->originalJsonArray = $dictionary;
            if (($resource instanceof AbstractIiifResource3 || $resource instanceof AbstractIiifResource || $resource instanceof AbstractIiifResource1) && $noParent) {
                $containedResources = [];
                foreach ($allResources as $id => $resourceArray) {
                    $containedResources[$id] = $resourceArray["resource"];
                }
                $containedResources[$resource->id] = $resource;
                $resource->containedResources = $containedResources;
            }
            $resource->executeAfterLoading();
            return $resource;
        } else {
            return $dictionary;
        }
    }

    protected function loadProperty($term, $value, JsonLdContext $context, &$allResources = array(), $processor) {
        $property = $term;
        if (strpos($property, "@") === 0) {
            $property = substr($property, 1);
        }
        if (array_key_exists($term, $this->getStringResources())) {
            if (is_string($value)) {
                $valueWithoutFragment = explode("#", $value)[0];
                if (array_key_exists($valueWithoutFragment, $allResources)) {
                    $this->$property = $allResources[$valueWithoutFragment]["resource"];
                } else {
                    $class = $this->getStringResources()[$term];
                    $resource = new $class();
                    $resource->id = $valueWithoutFragment;
                    self::registerResource($resource, $this->id, $term, $allResources);
                    $this->$property = $resource;
                }
            } elseif (is_array($value)) {
                $valueToSet = [];
                $register = [];
                foreach ($value as $v) {
                    $valueWithoutFragment = explode("#", $v)[0];
                    if (array_key_exists($valueWithoutFragment, $allResources)) {
                        $valueToSet[] = $allResources[$valueWithoutFragment]["resource"];
                    } else {
                        $class = $this->getStringResources()[$term];
                        $resource = new $class();
                        $resource->id = $valueWithoutFragment;
                        $valueToSet[] = $resource;
                        $register[] = $resource;
                    }
                }
                if (sizeof($register) > 0) {
                    self::registerResource($register, $this->id, $term, $allResources);
                }
                $this->$property = $valueToSet;
            }
            return;
        }
        $definition = $context->getTermDefinition($term);
        $result = null;
        if (JsonLdHelper::isSequentialArray($value)) {
            if (! $definition->hasListContainer() && ! $definition->hasSetContainer()) {
                // FIXME
                // throw new \Exception("array given for non collection property");
            }
            if ($definition->hasSetContainer() || $definition->hasListContainer() || array_search($term, $this->getCollectionProperties())!==false) {
                $result = array();
                foreach ($value as $member) {
                    if ($member == null || is_string($member)) {
                        $result[] = $member;
                    } elseif (JsonLdHelper::isDictionary($member)) {
                        if (array_search($term, $this->getTypelessProperties()) !== false) {
                            $resource = $this->getValueForTypelessProperty($term, $member, $context);
                        } else {
                            $resource = self::parseDictionary($member, $context, $allResources, $processor);
                        }
                        if (is_object($resource) && property_exists(get_class($resource), "id")) {
                            self::registerResource($resource, $this->id, $term, $allResources);
                        }
                        $result[] = $resource;
                    }
                }
                $this->$property = $result;
                return;
//             } elseif ($definition->hasListContainer()) {
//                 $result = array();
            }
        } elseif (JsonLdHelper::isDictionary($value)) {
            if (array_search($term, $this->getTypelessProperties()) !== false) {
                $termValue = $this->getValueForTypelessProperty($term, $value, $context);
            } else {
                $termValue = $this->parseDictionary($value, $context, $allResources, $processor);
            }
            if (is_object($termValue)) {
                self::registerResource($termValue, $this->id, $term, $allResources);
            }
            $this->$property = $termValue;
        } elseif (is_scalar($value)) {
//             $this->$property = $value;
            $v = $this->getSpecialTreatmentValue($property, $value, $context);
            $this->$property = $v;
            self::registerResource($v, $this->id, $term, $allResources);
            return;
        }
    }

    private static function registerResource(&$resource, $parentId, $property, &$allResources = array()) {
        if ($resource instanceof AbstractIiifEntity) {
            if (! array_key_exists($resource->id, $allResources) || ! $allResources[$resource->id]["resource"]->initialized) {
                if (array_key_exists($resource->id, $allResources) && array_key_exists("references", $allResources[$resource->id])) {
                    $references = $allResources[$resource->id]["references"];
                    foreach ($references as $reference) {
                        $refResource = $reference["resource"];
                        $refProperty = $reference["property"];
                        if (is_array($allResources[$refResource]["resource"]->$refProperty)) {
                            $index = 0; 
                            foreach ($allResources[$refResource]["resource"]->$refProperty as $item) {
                                if ($item->id = $resource->id) {
                                    break;
                                }
                                $index++;
                            }
                            $allResources[$refResource]["resource"]->$refProperty[$index] = &$resource;
                        } else {
                            $allResources[$refResource]["resource"]->$refProperty = &$resource;
                        }
                    }
                }
                $allResources[$resource->id]["resource"] = $resource;
            }
            if ($parentId != null && $property != null) {
                $allResources[$resource->id]["references"][] = [
                    "resource" => $parentId,
                    "property" => $property
                ];
            }
        } elseif (JsonLdHelper::isSequentialArray($resource) && $resource[0] instanceof AbstractIiifEntity) {
            foreach ($resource as $singleResource) {
                if (! array_key_exists($singleResource->id, $allResources) || ! $allResources[$singleResource->id]["resource"]->initialized) {
                    if (array_key_exists($singleResource->id, $allResources) && array_key_exists("references", $allResources[$singleResource->id])) {
                        $references = $allResources[$singleResource->id]["references"];
                        foreach ($references as $reference) {
                            $refResource = $reference["resource"];
                            $refProperty = $reference["property"];
                            $allResources[$refResource]["resource"]->$refProperty = &$singleResource;
                        }
                    }
                    $allResources[$singleResource->id]["resource"] = $singleResource;
                }
                
                if ($parentId != null && $property != null) {
                    $allResources[$singleResource->id]["references"][] = [
                        "resource" => $parentId,
                        "property" => $property
                    ];
                }
            }
        }
    }

    public function jsonPath($expression) {
        $jsonPath = new JSONPath($this->originalJsonArray);
        $results = $jsonPath->find($expression);
        if (is_array($results->data()) && count($results->data()) == 1) {
            return $results[0];
        }
        return $results;
    }

    public function getType() {
        return $this->type;
    }
    
    public function __construct($id = null) {
        if (isset($id)) {
            $this->id = $id;
        }
        
    }
    
    protected function sanitizeHtml($input, $options) {
        if (strpos($input, "<")===0 && strrpos($input, ">") === strlen($input)-1 && !($options&IiifResourceInterface::SANITIZE_NO_TAGS)) {
            $xsl = new \DOMDocument();
            if ($options&IiifResourceInterface::SANITIZE_NO_TAGS) {
                $xsl->load(__DIR__."/../../../../../resources/xslt/sanitize-notags.xsl");
            } else {
                $xsl->load(__DIR__."/../../../../../resources/xslt/sanitize.xsl");
            }
            
            $disableEntityLoaderBak = libxml_disable_entity_loader(true);
            $doc = new \DOMDocument();
            
            // We will only accept wellformed XML and therefore need an exception instead of a warning message  
            set_error_handler(function ($severity, $message, $filename, $lineno) {
                if (!($severity&(E_USER_NOTICE|E_NOTICE|E_DEPRECATED|E_USER_DEPRECATED))) {
                    throw new \ErrorException($message, null, $severity, $filename, $lineno);
                }
            });
            try {
                $doc->loadHTML($input , LIBXML_NOCDATA | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
            } catch (\ErrorException $ex) {
                $stripped = strip_tags($input);
                return ($options&(IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML|IiifResourceInterface::SANITIZE_XML_ENCODE_ALL)) ? htmlspecialchars($stripped,  ENT_QUOTES) : $stripped;
            } finally {
                restore_error_handler();
                libxml_disable_entity_loader($disableEntityLoaderBak);
            }
            
            $xslProcessor = new \XSLTProcessor();
            $xslProcessor->setSecurityPrefs(XSL_SECPREF_READ_FILE | XSL_SECPREF_WRITE_FILE | XSL_SECPREF_CREATE_DIRECTORY | XSL_SECPREF_READ_NETWORK | XSL_SECPREF_WRITE_NETWORK);
            $xslProcessor->importStyleSheet($xsl);
            $stripped = trim($xslProcessor->transformToXML($doc));
            $stripped = ($options&IiifResourceInterface::SANITIZE_XML_ENCODE_ALL) ? htmlspecialchars($stripped,  ENT_QUOTES) : $stripped;
            return $stripped;
        } else {
            $stripped = strip_tags($input);
            $stripped = ($options&(IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML|IiifResourceInterface::SANITIZE_XML_ENCODE_ALL)) ? htmlspecialchars($stripped,  ENT_QUOTES) : $stripped;
            return $stripped;
        }
        return null;
        
//         if (strpos($input, "<")===0 && strrpos($input, ">") === strlen($input)-1) {
//             $allowedTags = ($options&IiifResourceInterface::SANITIZE_NO_TAGS) ? [] : $this->getAllowedTags();
//             $tags = empty($allowedTags) ? null : ("<".implode("><", array_keys($allowedTags)).">");
//             $inputWithAllowedTags = strip_tags($input, $tags);
//             if ($options&IiifResourceInterface::SANITIZE_NO_TAGS) {
//                 return $inputWithAllowedTags;
//             }
//             $dom = new \DOMDocument();
//             $dom->loadHTML($inputWithAllowedTags, LIBXML_NOCDATA | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
//             $xpath = new \DOMXPath($dom);
//             $attributes = $xpath->query("//@*");
//             foreach ($attributes as $attribute) {
//                 $allowedAttributes = $allowedTags[$attribute->parentNode->nodeName];
//                 if (array_search($attribute->nodeName, $allowedAttributes) === false) {
//                     $attribute->parentNode->removeAttribute($attribute->nodeName);
//                 } elseif ($attribute->nodeName == "href" && strpos($attribute->textContent, "http:")!==0 && strpos($attribute->textContent, "https:")!==0 && strpos($attribute->textContent, "mailto:")!==0) {
//                     $attribute->parentNode->removeAttribute($attribute->nodeName);
//                 }
//             }
//             $stripped = trim($dom->saveHTML());
//             $stripped = ($options&IiifResourceInterface::SANITIZE_XML_ENCODE_ALL) ? htmlspecialchars($stripped,  ENT_QUOTES) : $stripped;
//             return $stripped;
//         } else {
//             $stripped = strip_tags($input);
//             $stripped = ($options&(IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML|IiifResourceInterface::SANITIZE_XML_ENCODE_ALL)) ? htmlspecialchars($stripped,  ENT_QUOTES) : $stripped;
//             return $stripped;
//         }
//         return null;
    }
    
    /**
     * Executed after resource has been eveluted. Used if any additional properties have to be set.
     */
    protected function executeAfterLoading() {
        // Do nothing
    }
    
}

