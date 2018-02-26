<?php
namespace iiif\model\resources;

use iiif\model\vocabulary\Names;
use iiif\model\vocabulary\MiscNames;

/**
 * Bundles all resource properties that every single iiif resource type may have 
 * see http://iiif.io/api/presentation/2.1/#resource-properties
 * 
 * @author lutzhelm
 *
 */
abstract class AbstractIiifResource
{
    // http://iiif.io/api/presentation/2.1/#technical-properties
    protected $id;
    protected $type;
    protected $viewingHint;
    
    // http://iiif.io/api/presentation/2.1/#descriptive-properties
    protected $label;
    protected $metadata;
    protected $description;
    protected $thumbnail;
    
    // http://iiif.io/api/presentation/2.1/#rights-and-licensing-properties
    protected $attribution;
    protected $license;
    protected $logo;
    
    // http://iiif.io/api/presentation/2.1/#linking-properties
    protected $related;
    protected $rendering;
    protected $service;
    protected $seeAlso;
    protected $within;

    protected $preferredLanguage;
    
    // keep it for faster and easier searches
    protected $originalJsonArray;
    
    protected $reference;
    
    public static function fromJson($jsonAsString)
    {
        $jsonAsArray = json_decode($jsonAsString, true);
        return static::fromArray($jsonAsArray);
    }

    abstract public static function fromArray($jsonAsArray);
    
    // could be static
    protected function getTranslatedField($field, $language)
    {
        if (is_null($field)) return null;
        if (is_string($field)) return $field;
        if (is_array($field)) {
            $selectedValue = $field[0];
            if (!(is_null($language))) {
                foreach ($field as $valueAndLanguage) {
                    if ($valueAndLanguage["@language"] == $language)
                    {
                        $selectedValue = $valueAndLanguage;
                    }
                }
            }
            return is_null($selectedValue ? null : $selectedValue["@value"]);
        }
        return null;
    }

    protected function getPreferredTranslation($field)
    {
        return $this->getTranslatedField($field, $this->preferredLanguage);
    }
    
    protected function loadPropertiesFromArray($jsonAsArray)
    {
        $this->originalJsonArray = $jsonAsArray;
        $this->id = array_key_exists(Names::ID, $jsonAsArray) ? $jsonAsArray[Names::ID] : null;
        $this->label = array_key_exists(Names::LABEL, $jsonAsArray) ? $jsonAsArray[Names::LABEL] : null;
        // TODO alle the other properties
    }
    
    // TODO check if one unified method for resource loading is possible
    // FIXME make this static
    protected function loadResources($jsonAsArray, $resourceFieldName, $resourceClass, &$targetArray)
    {
        if (!is_array($jsonAsArray))
        {
            throw new \Exception("$jsonAsArray ".$jsonAsArray." is not an array.");
        }
        if (array_key_exists($resourceFieldName, $jsonAsArray))
        {
            $resourcesAsArray = $jsonAsArray[$resourceFieldName];
            foreach ($resourcesAsArray as $resourceAsArray)
            {
                if (is_array($resourceAsArray)) {
                    $resource = $resourceClass::fromArray($resourceAsArray);
                }
                elseif (is_string($resourceAsArray)) {
                    $resource = new $resourceClass();
                    $resource->reference = true;
                    $resource->id = $resourceAsArray;
                }
                $targetArray[] = $resource;
            }
        }
    }

    // FIXME make this static
    protected function loadSingleResouce($jsonAsArray, $resourceFieldName, $resourceClass, &$targetField)
    {
        if (!is_array($jsonAsArray))
        {
            throw new \Exception("$jsonAsArray ".$jsonArray." is not an array.");
        }
        if (array_key_exists($resourceFieldName, $jsonAsArray))
        {
            $resourceAsArray = $jsonAsArray[$resourceFieldName];
            $resource = $resourceClass::fromArray($resourceAsArray);
            $targetField = $resource;
        }
    }
    
    // FIXME make this static
    protected function loadMixedResources($jsonAsArray, $resourceFieldName, $configArray, &$targetArray)
    {
        if (!is_array($jsonAsArray))
        {
            throw new \Exception("$jsonAsArray ".$jsonArray." is not an array.");
        }
        if (array_key_exists($resourceFieldName, $jsonAsArray))
        {
            $resourcesAsArray = $jsonAsArray[$resourceFieldName];
            foreach ($resourcesAsArray as $resourceAsArray)
            {
                foreach ($configArray as $config)
                {
                    if ($resourceAsArray[Names::TYPE]==$config[Names::TYPE])
                    {
                        $resourceClass = $config[MiscNames::CLAZZ];
                        $resource = $resourceClass::fromArray($resourceAsArray);
                        $targetArray[] = $resource;
                        break;
                    }
                }
            }
        }
    }
    
}

