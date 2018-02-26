<?php
namespace iiif\model\resources;

class ContentResource extends AbstractIiifResource
{
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray)
    {
        $contentResource = new ContentResource();
        $contentResource->loadPropertiesFromArray($jsonAsArray);
        
        return $contentResource;
    }

    
}

