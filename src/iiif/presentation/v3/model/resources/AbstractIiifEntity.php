<?php
namespace iiif\presentation\v3\model\resources;

use iiif\context\JsonLdContext;
use iiif\context\JsonLdProcessor;
use iiif\context\Keywords;

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
        "http://purl.org/dc/dcmitype/StillImage" => ContentResource3::class,
        "http://iiif.io/api/image/3/ImageService" => ImageService3::class,
        "http://rdfs.org/sioc/services#Service" => null,
        "http://purl.org/dc/dcmitype/Dataset" => ContentResource3::class,
        "http://purl.org/dc/dcmitype/Text" => ContentResource3::class,
        "http://www.w3.org/ns/oa#SpecificResource" => null,
        "http://www.w3.org/ns/oa#TextualBody" => null
    ];
    
    protected $originalJsonArray;
    /**
     * @var boolean
     */
    protected $initialized = false;
    
    /**
     * @return boolean
     */
    public function isInitialized() {
        return $this->initialized;
    }
    
    /**
     * 
     * @param array $dictionary
     * @param JsonLdContext $context
     * @return AbstractIiifEntity
     */
    protected static function parseDictionary(array $dictionary, JsonLdContext $context = null, array &$allResources = array()) {
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
            $allResources[$resource->id] = ["resource"=>$resource];
            return $resource;
        } else {
            // TODO ggf. Sonderbehandlung
            return $dictionary;
        }
    }
    
    protected function loadProperty($term, $value, JsonLdContext $context, array &$allResources = array()) {
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
                        self::registerResource($resource, $this->id, $term, $allResources);
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
                $this->$term = $this->parseDictionary($value, $context, $allResources);
//             }
        } elseif (is_string($value)) {
            $this->$term = $value;
            return;
        }
    }
    
    private static function registerResource(&$resource, $parentId, $property, array &$allResources = array()) {
        if ($resource instanceof AbstractIiifEntity) {
            if (!array_key_exists($resource->id, $allResources) || !$allResources[$resource->id]["resource"]->initialized) {
                // TODO bestehende Referenzen aktualisieren
                if (array_key_exists("references", $allResources[$resource->id])) {
                    $references = $allResources[$resource->id]["references"];
                    foreach ($references as $reference) {
                        $refResource = $reference["resource"];
                        $refProperty = $reference["property"];
                        $refResource->$refProperty = &$resource;
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

