<?php
namespace iiif\model\helper;

use iiif\model\vocabulary\Types;
use iiif\model\vocabulary\Names;

class IiifReader
{
    public static function getResourceClassForString($jsonAsString)
    {
        $jsonAsArray = json_decode($jsonAsString, true);
        return self::getResourceClassForArray($jsonAsArray);
    }
    public static function getResourceClassForArray($jsonAsArray)
    {
        if (!is_array($jsonAsArray) || !array_key_exists(Names::TYPE, $jsonAsArray))
        {
            return null;
        }
        $type = $jsonAsArray[Names::TYPE];
        if (array_key_exists($type, Types::IIIF_RESOURCE_TYPES))
        {
            return Types::IIIF_RESOURCE_TYPES[$type];
        }
        return null;
    }
    public static function getIiifResourceFromJsonString($jsonAsString)
    {
        $classForJson = self::getResourceClassForString($jsonAsString);
        return $classForJson::fromJson($jsonAsString);
    }
}

