<?php
namespace iiif\presentation\v3\model\resources;

use iiif\context\IRI;
use iiif\context\JsonLdContext;
use iiif\context\JsonLdProcessor;
use iiif\context\Keywords;

abstract class AbstractIiifResource3
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
        "http://www.w3.org/ns/oa#SpecificResource" => null
    ];
    
    protected $id;
    protected $type;
    protected $behaviour;
    
    protected $label;
    protected $metadata;
    protected $summary;
    protected $thumbnail;
    protected $requiredStatement;
    protected $rights;
    
    protected $seeAlso;
    protected $service;
    protected $logo;
    protected $homepage;
    protected $rendering;
    protected $partOf;
    
    protected $originalJsonArray;
    
    public static function loadIiifResource($resource)
    {
        if (is_string($resource) && IRI::isAbsoluteIri($resource)) {
            $resource = file_get_contents($resource);
        }
        if (is_string($resource)) {
            $resource = json_decode($resource, true);
        }
        if (JsonLdProcessor::isDictionary($resource)) {
            $r = self::parseDictionary($resource);
            return $r;
        }
        return null;
    }

    protected static function parseDictionary($dictionary, JsonLdContext $context = null) {
        if (array_key_exists(Keywords::CONTEXT, $dictionary)) {
            $processor = new JsonLdProcessor();
            $context = $processor->processContext($dictionary[Keywords::CONTEXT], new JsonLdContext());
        }
        if (array_key_exists("type", $dictionary)) {
            $type = $dictionary["type"];
            $typeTerm = $context->getTermDefinition($type);
            $typeIri = $typeTerm->getIriMapping();
            $typeClass = self::CLASSES[$typeIri];
            if ($typeClass == null) return null;
            $resource = new $typeClass();
            foreach ($dictionary as $key=>$value) {
                if ($key == "type") continue;
                if ($key == Keywords::CONTEXT) continue;
                if ($context->getTermDefinition($key) == null) continue;
                $resource->$key = self::loadProperty($key, $value, $context);
            }
            return $resource;
        } else {
            
        }
    }
    
    protected static function loadProperty($term, $value, JsonLdContext $context) {
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
                        $result[] = self::parseDictionary($member, $context);
                    }
                }
                return $result;
            } elseif ($definition->hasListContainer()) {
                $result = array();
            }
        } elseif (JsonLdProcessor::isDictionary($value)) {
            // ??
        } elseif (is_string($value)) {
            return $value;
        }
        return null;
    }
    
    public function __set($var, $value) {
        $this->$var = $value;
    }
}




