<?php
namespace iiif\presentation\common\model;

use iiif\context\IRI;
use iiif\context\JsonLdContext;
use iiif\context\JsonLdProcessor;
use iiif\context\Keywords;
use iiif\presentation\v2\model\resources\AbstractIiifResource;
use iiif\presentation\v2\model\resources\AnnotationList;
use iiif\presentation\v2\model\resources\Canvas;
use iiif\presentation\v2\model\resources\ContentResource;
use iiif\presentation\v2\model\resources\Manifest;
use iiif\presentation\v2\model\resources\Range;
use iiif\presentation\v2\model\resources\Sequence;
use iiif\presentation\v3\model\resources\AbstractIiifResource3;
use iiif\presentation\v3\model\resources\Annotation3;
use iiif\presentation\v3\model\resources\AnnotationCollection3;
use iiif\presentation\v3\model\resources\AnnotationPage3;
use iiif\presentation\v3\model\resources\Canvas3;
use iiif\presentation\v3\model\resources\Collection3;
use iiif\presentation\v3\model\resources\ContentResource3;
use iiif\presentation\v3\model\resources\Manifest3;
use iiif\presentation\v3\model\resources\Range3;
use iiif\presentation\v3\model\resources\SpecificResource3;
use iiif\services\ImageInformation3;
use iiif\services\ImageInformation2;
use iiif\services\ImageInformation1;

abstract class AbstractIiifEntity
{
    protected static $CLASSES = [
        "http://iiif.io/api/presentation/2#Manifest" => Manifest::class,
        "http://iiif.io/api/presentation/2#Sequence" => Sequence::class,
        "http://iiif.io/api/presentation/2#Canvas" => Canvas::class,
        "http://iiif.io/api/presentation/2#AnnotationList" => AnnotationList::class,
        "http://iiif.io/api/presentation/2#Range" => Range::class,
        "http://iiif.io/api/presentation/2#Layer" => null,
        "http://iiif.io/api/presentation/3#Collection" => Collection3::class,
        "http://iiif.io/api/presentation/3#Manifest" => Manifest3::class,
        "http://iiif.io/api/presentation/3#Canvas" => Canvas3::class,
        "http://iiif.io/api/presentation/3#Range" => Range3::class,
        "http://www.w3.org/ns/oa#Annotation" => Annotation3::class,
        "http://www.w3.org/ns/activitystreams#OrderedCollectionPage" => AnnotationPage3::class,
        "http://www.w3.org/ns/activitystreams#OrderedCollection" => AnnotationCollection3::class,
        "http://www.w3.org/ns/activitystreams#Application" => null,
        "http://purl.org/dc/dcmitype/StillImage" => ContentResource3::class,
        "http://purl.org/dc/dcmitype/Image" => ContentResource::class,
        "http://iiif.io/api/image/1/ImageService" => ImageInformation1::class,
        "http://iiif.io/api/image/2/ImageService" => ImageInformation2::class,
        "http://iiif.io/api/image/3/ImageService" => ImageInformation3::class,
        "http://rdfs.org/sioc/services#Service" => null,
        "http://purl.org/dc/dcmitype/Dataset" => ContentResource3::class,
        "http://purl.org/dc/dcmitype/Text" => ContentResource3::class,
        "http://www.w3.org/ns/oa#SpecificResource" => SpecificResource3::class,
        "http://www.w3.org/ns/oa#TextualBody" => null,
        "http://www.w3.org/ns/oa#FragmentSelector" => null,
        "http://www.w3.org/ns/oa#PointSelector" => null,
    ];
    
    /**
     * 
     * @var array
     */
    protected $originalJsonArray;
    /**
     * @var boolean
     */
    protected $initialized = false;
    
    protected $containedResources;
    
    /**
     * @return boolean
     */
    public function isInitialized() {
        return $this->initialized;
    }

    /**
     * @return array
     */
    public function getOriginalJsonArray()
    {
        return $this->originalJsonArray;
    }

    /**
     * Properties that link to IIIF resources via URI string rather than id:URI/type:type dictionary.
     * @return array
     */
    protected function getStringResources() {
        return [];
    }
    
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
        if (array_key_exists($typeOrAlias, $dictionary)) {
            $type = $dictionary[$typeOrAlias];
            $id = array_key_exists($idOrAlias, $dictionary) ? $dictionary[$idOrAlias] : null;
            if ($id != null && array_key_exists($id, $allResources)) {
                $resource = $allResources[$id]["resource"];
            } else {
                $typeIri = IRI::isAbsoluteIri($type) ? $type : IRI::isCompactUri($type) ? $processor->expandIRI($context, $type): $context->getTermDefinition($type)->getIriMapping();
                $typeClass = self::$CLASSES[$typeIri];
                if ($typeClass == null) {
                    return $dictionary;
                }
                $resource = new $typeClass();
            }
            /* @var $resource AbstractIiifEntity; */
            if (!$resource->initialized 
                || 
                sizeof(array_diff(array_keys($dictionary), [$typeOrAlias, $idOrAlias])) > 0) {
                foreach ($dictionary as $key=>$value) {
                    if ($key == $typeOrAlias) continue;
                    if ($key == Keywords::CONTEXT) continue;
                    if (!Keywords::isKeyword($key) && $context->getTermDefinition($key) == null) continue;
                    $resource->loadProperty($key, $value, $context, $allResources, $processor);
                    if ($key != $idOrAlias) {
                        $resource->initialized = true;
                    }
                }
            }
            $resource->originalJsonArray = $dictionary;
            if (($resource instanceof AbstractIiifResource3 || $resource instanceof AbstractIiifResource)  && $noParent) {
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
        if (strpos($property, "@")===0) {
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
                    //$allResources[$value] = $resource;
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
                if (sizeof($register)>0) {
                    self::registerResource($register, $this->id, $term, $allResources);
                }
                $this->$property = $valueToSet;
            }
            return;
        }
        $definition = $context->getTermDefinition($term);
        $result = null;
        if (JsonLdProcessor::isSequentialArray($value)) {
            if (!$definition->hasListContainer() && !$definition->hasSetContainer()){
                // FIXME
                // throw new \Exception("array given for non collection property");
            }
            if ($definition->hasSetContainer() || $definition->hasListContainer()) {
                $result = array();
                foreach ($value as $member) {
                    if ($member == null || is_string($member)) {
                        $result[] = $member;
                    } elseif (JsonLdProcessor::isDictionary($member)) {
                        $resource = self::parseDictionary($member, $context, $allResources, $processor);
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
            $termValue = $this->parseDictionary($value, $context, $allResources, $processor);
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
            if (!array_key_exists($resource->id, $allResources) || !$allResources[$resource->id]["resource"]->initialized) {
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
            if ($parentId!=null && $property!=null) {
                $allResources[$resource->id]["references"][] = ["resource"=>$parentId, "property"=>$property];
            }
        } elseif (JsonLdProcessor::isSequentialArray($resource) && $resource[0] instanceof AbstractIiifEntity) {
            foreach ($resource as $singleResource) {
                if (!array_key_exists($singleResource->id, $allResources) || !$allResources[$singleResource->id]["resource"]->initialized) {
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
                
                
                if ($parentId!=null && $property!=null) {
                    $allResources[$singleResource->id]["references"][] = ["resource"=>$parentId, "property"=>$property];
                }
            }
        }
    }
}

