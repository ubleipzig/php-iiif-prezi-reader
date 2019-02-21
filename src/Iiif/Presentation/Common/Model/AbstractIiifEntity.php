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

namespace Ubl\Iiif\Presentation\Common\Model;

use Flow\JSONPath\JSONPath;
use Ubl\Iiif\Context\IRI;
use Ubl\Iiif\Context\JsonLdContext;
use Ubl\Iiif\Context\JsonLdHelper;
use Ubl\Iiif\Context\JsonLdProcessor;
use Ubl\Iiif\Context\Keywords;
use Ubl\Iiif\Presentation\Common\TypeMap;
use Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface;
use Ubl\Iiif\Presentation\V1\Model\Resources\AbstractIiifResource1;
use Ubl\Iiif\Presentation\V2\Model\Resources\AbstractIiifResource2;
use Ubl\Iiif\Presentation\V3\Model\Resources\AbstractIiifResource3;
use Ubl\Iiif\Tools\IiifHelper;

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
    
    /**
     * Resource properties in the JSON-LD document that have not been defined by the
     * IIIF Presentation or Image API are collected in $otherdata. 
     * 
     * @var array
     */
    protected $otherData;

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

    protected function getPropertyMap() {
        return [
            "@id" => "id",
            "@type" => "type"
        ];
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
        if (array_key_exists(Keywords::TYPE, $dictionary) || array_key_exists($typeOrAlias, $dictionary) || array_key_exists(Keywords::CONTEXT, $dictionary)) {
            $type = null;
            if (array_key_exists($typeOrAlias, $dictionary)) {
                $type = $dictionary[$typeOrAlias]; 
            } elseif (array_key_exists(Keywords::TYPE, $dictionary)) {
                // Even if "@type" has an alias like "type", "@type" might still be used.
                $type = $dictionary[Keywords::TYPE];
            }
            $localContext = null;
            if (array_key_exists(Keywords::CONTEXT, $dictionary)) {
                $localContext = $dictionary[Keywords::CONTEXT];
            }
            $id = null;
            if (array_key_exists(Keywords::ID, $dictionary)) {
                $id = $dictionary[Keywords::ID];
            } elseif (array_key_exists($idOrAlias, $dictionary)) {
                $id = $dictionary[$idOrAlias];
            }
            if ($id != null && array_key_exists($id, $allResources)) {
                $resource = $allResources[$id]["resource"];
            } else {
                $typeIri = $type;
                if (IRI::isCompactIri($type, $context)) {
                    $typeIri = $processor->expandIRI($context, $type);
                } elseif (!IRI::isAbsoluteIri($type) && $context->getTermDefinition($type) != null) {
                    $typeIri = $context->getTermDefinition($type)->getIriMapping();
                }
                $typeClass = TypeMap::getClassForType($typeIri, $context);
                if ($typeClass == null) {
                    $typeClass = TypeMap::getClassForContext($localContext, $context);
                }
                if ($typeClass == null) {
                    return $dictionary;
                }
                $resource = new $typeClass();
            }
            /* @var $resource \AbstractIiifEntity; */
            if (! $resource->initialized || sizeof(array_diff(array_keys($dictionary), [
                // FIXME The existance of a keyword alias does not rule out the use of a keyword.
                $typeOrAlias,
                $idOrAlias
            ])) > 0) {
                $dictionaryOfDictionaries = [];
                foreach ($dictionary as $key => $value) {
                    if ($key == Keywords::TYPE || $key == $typeOrAlias) {
                        $resource->type = $value;
                        continue;
                    }
                    if ($key == Keywords::CONTEXT) {
                        continue;
                    }
//                     if (! Keywords::isKeyword($key) && $context->getTermDefinition($key) == null) {
//                         continue;
//                     }
                    if (JsonLdHelper::isDictionary($value)) {
                        // set basic values first
                        $dictionaryOfDictionaries[$key] = $value;
                        continue;
                    }
                    $resource->loadProperty($key, $value, $context, $allResources, $processor);
                    if ($key != Keywords::ID && $key != $idOrAlias) {
                        $resource->initialized = true;
                    }
                }
                foreach ($dictionaryOfDictionaries as $key => $value) {
                    $resource->loadProperty($key, $value, $context, $allResources, $processor);
                    if ($key != $idOrAlias && $key != Keywords::ID) {
                        $resource->initialized = true;
                    }
                }
            }
            $resource->originalJsonArray = $dictionary;
            if (($resource instanceof AbstractIiifResource3 || $resource instanceof AbstractIiifResource2 || $resource instanceof AbstractIiifResource1) && $noParent) {
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
        if (!property_exists($this, $property)) {
            $iriOrKeyword = null;
            if ($context->getTermDefinition($property) != null) {
                $iriOrKeyword = $context->getTermDefinition($property)->getIriMapping();
            } elseif (IRI::isCompactIri($property, $context)) {
                $iriOrKeyword = $context->expandIRI($property);
            } elseif (Keywords::isKeyword($property) || IRI::isAbsoluteIri($property)) {
                $iriOrKeyword = $property;
            }
            $propertyMap = $this->getPropertyMap();
            if (array_key_exists($iriOrKeyword, $propertyMap)) {
                $property = $propertyMap[$iriOrKeyword];
            } else {
                $this->otherData[$term] = $value;
                return;
            }
        }
        if (strpos($property, "@") === 0) {
            $property = substr($property, 1);
        }
        if (array_key_exists($property, $this->getStringResources())) {
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
        if (is_array($results->data()) && count($results->data()) <= 1) {
            return empty(count($results->data())) ? null : $results[0];
        }
        return $results;
    }

    public function getType() {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getOtherData() {
        return $this->otherData;
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
