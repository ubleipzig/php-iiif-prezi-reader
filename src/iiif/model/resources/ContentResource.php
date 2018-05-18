<?php
namespace iiif\model\resources;


use iiif\model\vocabulary\Names;

class ContentResource extends AbstractIiifResource
{
    protected $format;
    protected $chars;
    
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources=array())
    {
        $contentResource = self::createInstanceFromArray($jsonAsArray, $allResources);
        $contentResource->loadPropertiesFromArray($jsonAsArray, $allResources);
        $contentResource->format = array_key_exists(Names::FORMAT, $jsonAsArray) ? $jsonAsArray[Names::FORMAT] : null;
        $contentResource->chars = array_key_exists(Names::CHARS, $jsonAsArray) ? $jsonAsArray[Names::CHARS] : null;
        return $contentResource;
    }
    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }
}

