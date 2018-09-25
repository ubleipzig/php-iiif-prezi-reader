<?php
namespace iiif\presentation;

use iiif\context\IRI;
use iiif\context\Keywords;
use iiif\presentation\v2\model\helper\IiifReader;
use iiif\presentation\v3\model\resources\AbstractIiifResource3;

class IiifHelper
{
    CONST PRESENTATION_API_2_CONTEXT = "http://iiif.io/api/presentation/2/context.json";
    CONST PRESENTATION_API_3_CONTEXT = "http://iiif.io/api/presentation/3/context.json";
    
    public static function loadIiifResource($resource) {
        if (is_string($resource)) {
            if (IRI::isAbsoluteIri($resource) && parse_url($resource)) {
                $resource = file_get_contents($resource);
            }
            $resource = json_decode($resource, true);
        }
        if (is_array($resource)) {
            if (array_key_exists(Keywords::CONTEXT, $resource)) {
                $context = $resource[Keywords::CONTEXT];
                if (is_array($context)) {
                    foreach ($context as $ctx) {
                        $iiif = self::getResourceForContext($ctx, $resource);
                        if ($iiif!=null) {
                            return $iiif;
                        }
                    }
                } else {
                    return self::getResourceForContext($context, $resource);
                }
            }
        }
        return null;
    }
    
    private static function getResourceForContext($context, $resource) {
        if ($context == self::PRESENTATION_API_2_CONTEXT) {
            $iiifClass = IiifReader::getResourceClassForArray($resource);
            return $iiifClass::fromArray($resource);
        }
        if ($context == self::PRESENTATION_API_3_CONTEXT) {
            return AbstractIiifResource3::loadIiifResource($resource);
        }
        return null;
    }
}

