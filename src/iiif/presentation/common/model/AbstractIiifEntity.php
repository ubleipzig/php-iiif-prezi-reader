<?php
namespace iiif\presentation\common\model;

use Flow\JSONPath\JSONPath;
use iiif\context\IRI;
use iiif\context\JsonLdContext;
use iiif\context\JsonLdProcessor;
use iiif\context\Keywords;
use iiif\presentation\common\TypeMap;
use iiif\presentation\v2\model\resources\AbstractIiifResource;
use iiif\presentation\v3\model\resources\AbstractIiifResource3;

abstract class AbstractIiifEntity {

    /**
     *
     * @var array
     */
    protected $originalJsonArray;

    /**
     *
     * @var boolean
     */
    protected $initialized = false;

    protected $containedResources;

    protected $type;

    public static function loadIiifResource($resource) {
        if (is_string($resource) && IRI::isAbsoluteIri($resource)) {
            $resource = file_get_contents($resource);
        }
        if (is_string($resource)) {
            $resource = json_decode($resource, true);
        }
        if (JsonLdProcessor::isDictionary($resource)) {
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

    protected function getValueForTypelessProperty(string $property, array $dictionary, JsonLdContext $context) {}

    /**
     *
     * @param array $dictionary
     * @param JsonLdContext $context
     * @return AbstractIiifEntity
     */
    protected static function parseDictionary(array $dictionary, JsonLdContext $context = null, array &$allResources = array(), $processor = null) {
        $noParent = $context === null;
        if (array_key_exists(Keywords::CONTEXT, $dictionary)) {
            $processor = new JsonLdProcessor();
            $context = $processor->processContext($dictionary[Keywords::CONTEXT], new JsonLdContext());
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
                $typeIri = IRI::isAbsoluteIri($type) ? $type : IRI::isCompactUri($type) ? $processor->expandIRI($context, $type) : $context->getTermDefinition($type)->getIriMapping();
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
                foreach ($dictionary as $key => $value) {
                    if ($key == $typeOrAlias) {
                        $resource->type = $value;
                        continue;
                    }
                    if ($key == Keywords::CONTEXT)
                        continue;
                    if (! Keywords::isKeyword($key) && $context->getTermDefinition($key) == null)
                        continue;
                    $resource->loadProperty($key, $value, $context, $allResources, $processor);
                    if ($key != $idOrAlias) {
                        $resource->initialized = true;
                    }
                }
            }
            $resource->originalJsonArray = $dictionary;
            if (($resource instanceof AbstractIiifResource3 || $resource instanceof AbstractIiifResource) && $noParent) {
                $containedResources = [];
                foreach ($allResources as $id => $resourceArray) {
                    $containedResources[$id] = $resourceArray["resource"];
                }
                $containedResources[$resource->id] = $resource;
                $resource->containedResources = $containedResources;
            }
            return $resource;
        } else {
            return $dictionary;
        }
    }

    protected function loadProperty($term, $value, JsonLdContext $context, array &$allResources = array(), $processor) {
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
        if (JsonLdProcessor::isSequentialArray($value)) {
            if (! $definition->hasListContainer() && ! $definition->hasSetContainer()) {
                // FIXME
                // throw new \Exception("array given for non collection property");
            }
            if ($definition->hasSetContainer() || $definition->hasListContainer()) {
                $result = array();
                foreach ($value as $member) {
                    if ($member == null || is_string($member)) {
                        $result[] = $member;
                    } elseif (JsonLdProcessor::isDictionary($member)) {
                        if (array_key_exists($term, $this->getTypelessProperties())) {
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
            } elseif ($definition->hasListContainer()) {
                $result = array();
            }
        } elseif (JsonLdProcessor::isDictionary($value)) {
            if (array_key_exists($term, $this->getTypelessProperties())) {
                $termValue = $this->getValueForTypelessProperty($term, $value, $context);
            } else {
                $termValue = $this->parseDictionary($value, $context, $allResources, $processor);
            }
            if (is_object($termValue)) {
                self::registerResource($termValue, $this->id, $term, $allResources);
            }
            $this->$property = $termValue;
        } elseif (is_scalar($value)) {
            $this->$property = $value;
            return;
        }
    }

    private static function registerResource(&$resource, $parentId, $property, array &$allResources = array()) {
        if ($resource instanceof AbstractIiifEntity) {
            if (! array_key_exists($resource->id, $allResources) || ! $allResources[$resource->id]["resource"]->initialized) {
                if (array_key_exists($resource->id, $allResources) && array_key_exists("references", $allResources[$resource->id])) {
                    $references = $allResources[$resource->id]["references"];
                    foreach ($references as $reference) {
                        $refResource = $reference["resource"];
                        $refProperty = $reference["property"];
                        $allResources[$refResource]["resource"]->$refProperty = &$resource;
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
        } elseif (JsonLdProcessor::isSequentialArray($resource) && $resource[0] instanceof AbstractIiifEntity) {
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

    public function jsonPath(string $expression) {
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
}

