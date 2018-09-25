<?php
namespace iiif\presentation\common\model;

use iiif\context\JsonLdContext;
use iiif\context\JsonLdProcessor;
use iiif\context\Keywords;
use iiif\presentation\v3\model\resources\Collection3;
use iiif\presentation\v3\model\resources\Manifest3;
use iiif\presentation\v3\model\resources\Canvas3;
use iiif\presentation\v3\model\resources\Range3;
use iiif\presentation\v3\model\resources\Annotation3;
use iiif\presentation\v3\model\resources\AnnotationPage3;
use iiif\presentation\v3\model\resources\AnnotationCollection3;
use iiif\presentation\v3\model\resources\ContentResource3;
use iiif\presentation\v3\model\resources\ImageService3;
use iiif\presentation\v3\model\resources\SpecificResource3;
use iiif\presentation\v3\model\resources\AbstractIiifResource3;

abstract class AbstractIiifEntity
{
    CONST CLASSES = [
        "http://iiif.io/api/presentation/3#Collection" => Collection3::class,
        "http://iiif.io/api/presentation/3#Manifest" => Manifest3::class,
        "http://iiif.io/api/presentation/3#Canvas" => Canvas3::class,
        "http://iiif.io/api/presentation/3#Range" => Range3::class,
        "http://www.w3.org/ns/oa#Annotation" => Annotation3::class,
        "http://www.w3.org/ns/activitystreams#OrderedCollectionPage" => AnnotationPage3::class,
        "http://www.w3.org/ns/activitystreams#OrderedCollection" => AnnotationCollection3::class,
        "http://www.w3.org/ns/activitystreams#Application" => null,
        "http://purl.org/dc/dcmitype/StillImage" => ContentResource3::class,
        "http://iiif.io/api/image/1/ImageService" => null,
        "http://iiif.io/api/image/2/ImageService" => null,
        "http://iiif.io/api/image/3/ImageService" => ImageService3::class,
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
    protected static function parseDictionary(array $dictionary, JsonLdContext $context = null, array &$allResources = array()) {
        $noParent = $context === null;
        if (array_key_exists(Keywords::CONTEXT, $dictionary)) {
            $processor = new JsonLdProcessor();
            $context = $processor->processContext($dictionary[Keywords::CONTEXT], new JsonLdContext());
        }
        if (array_key_exists("type", $dictionary)) {
            $type = $dictionary["type"];
            $id = array_key_exists("id", $dictionary) ? $dictionary["id"] : null;
            if ($id != null && array_key_exists($id, $allResources)) {
                $resource = $allResources[$id]["resource"];
            } else {
                $typeTerm = $context->getTermDefinition($type);
                $typeIri = $typeTerm->getIriMapping();
                $typeClass = self::CLASSES[$typeIri];
                if ($typeClass == null) {
                    echo "no type for ".$typeIri."\n";
                    return null;
                }
                $resource = new $typeClass();
            }
            /* @var $resource AbstractIiifEntity; */
            if (!$resource->initialized) {
                foreach ($dictionary as $key=>$value) {
                    if ($key == "type") continue;
                    if ($key == Keywords::CONTEXT) continue;
                    if ($context->getTermDefinition($key) == null) continue;
                    $resource->loadProperty($key, $value, $context, $allResources);
                    if ($key != "id") {
                        $resource->initialized = true;
                    }
                }
            }
            $resource->originalJsonArray = $dictionary;
//             if (property_exists(get_class($resource), "id")) {
//                 $parentId = $parent == null ? null : $parent->id;
//                 self::registerResource($resource, $parentId, null);
//                 //$allResources[$resource->id] = ["resource"=>$resource];
//             }

            if ($resource instanceof AbstractIiifResource3 && $noParent) {
                $containedResources = [];
                foreach ($allResources as $id => $resourceArray) {
                    $containedResources[$id] = $resourceArray["resource"];
                }
                $resource->containedResources = $containedResources;
            }
            return $resource;
        } else {
            // TODO ggf. Sonderbehandlung
            return $dictionary;
        }
    }
    
    protected function loadProperty($term, $value, JsonLdContext $context, array &$allResources = array()) {
        if (array_key_exists($term, $this->getStringResources())) {
            if (array_key_exists($value, $allResources)) {
                $this->$term = $allResources[$value]["resource"];
            } else {
                $class = $this->getStringResources()[$term];
                $resource = new $class();
                $resource->id = $value;
                self::registerResource($resource, $this->id, $term, $allResources);
                //$allResources[$value] = $resource;
                $this->$term = $resource;
            }
            return;
        }
        $definition = $context->getTermDefinition($term);
        $result = null;
        if (JsonLdProcessor::isSequentialArray($value)) {
            if (!$definition->hasListContainer() && !$definition->hasSetContainer()){
                throw new \Exception("array given for non collection property");
            }
            if ($definition->hasSetContainer() || $definition->hasListContainer()) {
                $result = array();
                foreach ($value as $member) {
                    if ($member == null || is_string($member)) {
                        $result[] = $member;
                    } elseif (JsonLdProcessor::isDictionary($member)) {
                        $resource = self::parseDictionary($member, $context, $allResources);
                        if (is_object($resource) && property_exists(get_class($resource), "id")) {
                            self::registerResource($resource, $this->id, $term, $allResources);
                        }
                        $result[] = $resource;
                    }
                }
                $this->$term = $result;
                return;
            } elseif ($definition->hasListContainer()) {
                $result = array();
            }
        } elseif (JsonLdProcessor::isDictionary($value)) {
//             if ($definition->hasLanguageContainer() || $term == "requiredStatement") {
//                 $this->$term = $value;
//             } else {
//             }
            $termValue = $this->parseDictionary($value, $context, $allResources);
            if (is_object($termValue)) {
                self::registerResource($termValue, $this->id, $term, $allResources);
            }
            $this->$term = $termValue;
        } elseif (is_string($value)) {
            $this->$term = $value;
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
        }
    }
}

