<?php
namespace iiif\model\resources;

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
    
    // no format
    // no height
    // no width
    // no viewingDirection
    // no navDate
    
    
    // no startCanvas
    // no contentLayer
    
    public static function fromJson($jsonAsString)
    {
        $jsonAsArray = json_decode($jsonAsString, true);
        return fromArray($jsonAsArray);
    }

    abstract public static function fromArray($jsonAsArray);

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
        $this->id = $jsonAsArray['id'];
        $this->label = $jsonAsArray['label'];
        // TODO
    }
}

