<?php
namespace iiif\model\resources;


use iiif\model\vocabulary\Names;

class ContentResource extends AbstractIiifResource
{
    protected $format;
    
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray)
    {
        $contentResource = new ContentResource();
        $contentResource->loadPropertiesFromArray($jsonAsArray);
        
        $contentResource->format = array_key_exists(Names::FORMAT, $jsonAsArray) ? $jsonAsArray[Names::FORMAT] : null;
        
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

