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
use Ubl\Iiif\IiifException;
use Ubl\Iiif\Context\IRI;
use Ubl\Iiif\Context\JsonLdHelper;
use Ubl\Iiif\Context\Keywords;
use Ubl\Iiif\Presentation\Common\TypeHelper;
use Ubl\Iiif\Presentation\V1\Model\Resources\AbstractIiifResource1;
use Ubl\Iiif\Presentation\V2\Model\Resources\AbstractIiifResource2;
use Ubl\Iiif\Presentation\V3\Model\Resources\AbstractIiifResource3;
use Ubl\Iiif\Tools\IiifHelper;
use Ubl\Iiif\Tools\IiifHtmlSanitizer;

abstract class AbstractIiifEntity {

    protected ?string $id = null;

    /**
     *
     * @var array
     */
    protected $originalJsonArray;

    /**
     * Only used during initialization / loading of a IIIF document
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

    /**
     * 
     * @param string|array $resource IRI, text content or array representation of IIIF document
     * @return \Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity|NULL
     */
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
     * @return string[] A map to get the right property name for a given IRI or JSON-LD keyword.
     */
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
     * Names of properties that can be instantiated as AbstractIiifEntity but have not @type given.
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
     * Names of properties that may contain values as a sequential array but whose term definition does not
     * have the suiting @container property. Mostly used for missing @set in Presentation API 2.
     * @return array
     */
    protected function getCollectionProperties() {
        return [];
    }

    protected function getValueForTypelessProperty($property, $dictionary, $context) {}

    protected static function parseDictionary(array $dictionary, $context = null, &$allResources = array()) {
        $noParent = $context == null;
        $localContext = TypeHelper::getIiifContextIri($dictionary);
        if ($localContext != null) {
            $context = $localContext;
        }
        $typeClass = TypeHelper::getClass($dictionary, $context);
        if (isset($typeClass)) {
            $idOrAlias = TypeHelper::getKeywordOrAlias($context, Keywords::ID);
            $typeOrAlias = TypeHelper::getKeywordOrAlias($context, Keywords::TYPE);
            $id = null;
            if (array_key_exists($idOrAlias, $dictionary)) {
                $id = $dictionary[$idOrAlias];
            }
            if ($id != null && array_key_exists($id, $allResources)) {
                $resource = $allResources[$id]["resource"];
            } else {
                $resource = new $typeClass;
            }
            /* @var $resource \AbstractIiifEntity; */
            if (!$resource->initialized || sizeof(array_diff(array_keys($dictionary), [$typeOrAlias,$idOrAlias])) > 0) {
                $dictionaryOfDictionaries = [];
                foreach ($dictionary as $key => $value) {
                    if ($key == Keywords::TYPE || $key == $typeOrAlias) {
                        $resource->type = $value;
                        continue;
                    }
                    if ($key == Keywords::CONTEXT) {
                        continue;
                    }
                    if (JsonLdHelper::isDictionary($value)) {
                        // set basic values first
                        $dictionaryOfDictionaries[$key] = $value;
                        continue;
                    }
                    $resource->loadProperty($key, $value, $context, $allResources);
                    if ($key != Keywords::ID && $key != $idOrAlias) {
                        $resource->initialized = true;
                    }
                }
                foreach ($dictionaryOfDictionaries as $key => $value) {
                    $resource->loadProperty($key, $value, $context, $allResources);
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

    protected function loadProperty($term, $value, $context, &$allResources = array()) {
        $property = $term;
        if (strpos($property, "@") === 0) {
            $property = substr($property, 1);
        }
        if (!property_exists($this, $property)) {
            $this->otherData[$term] = $value;
            return;
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
        $result = null;
        if (JsonLdHelper::isSimpleArray($value)) {
            //if ($definition->hasSetContainer() || $definition->hasListContainer() || array_search($term, $this->getCollectionProperties())!==false) {
                $result = array();
                foreach ($value as $member) {
                    if ($member == null || is_string($member)) {
                        $result[] = $member;
                    } elseif (JsonLdHelper::isDictionary($member)) {
                        if (array_search($term, $this->getTypelessProperties()) !== false) {
                            $resource = $this->getValueForTypelessProperty($term, $member, $context);
                        } else {
                            $resource = self::parseDictionary($member, $context, $allResources);
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
            //}
        } elseif (JsonLdHelper::isDictionary($value)) {
            if (array_search($term, $this->getTypelessProperties()) !== false) {
                $termValue = $this->getValueForTypelessProperty($term, $value, $context);
            } else {
                $termValue = $this->parseDictionary($value, $context, $allResources);
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
        } elseif (JsonLdHelper::isSimpleArray($resource) && $resource[0] instanceof AbstractIiifEntity) {
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
        if (is_array($results->getData()) && count($results->getData()) <= 1) {
            return empty(count($results->getData())) ? null : $results[0];
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
        return IiifHtmlSanitizer::sanitizeHtml($input);
    }
    
    /**
     * Executed after resource has been eveluted. Used if any additional properties have to be set.
     */
    protected function executeAfterLoading() {
        // Do nothing
    }

    public function loadLazy() {
        if ($this->isLinkedResource()) {
            $remoteResource = self::loadIiifResource($this->id);
            if (get_class($this) != get_class($remoteResource)) {
                throw new IiifException("Lazy loaded resource has different type or context than linked resource.");
            }
            foreach (get_object_vars($remoteResource) as $property => &$value) {
                if (is_scalar($value)) {
                    $this->$property = $value;
                } else {
                    $this->$property = &$value;
                }
            }
            $this->initialized = true;
        }
    }
    
    public function isLinkedResource() {
        $embeddedProperties = $this->getEmbeddedProperties();
        foreach ($this->originalJsonArray as $key => $value) {
            if (array_search($key, $embeddedProperties) === false) {
                return false;
            }
        }
        return true;
    }

    protected function getEmbeddedProperties() {
        return ["@id", "id", "@type", "type", "label", "@context", "profile", "format"];
    }
    
}
