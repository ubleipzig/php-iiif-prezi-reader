<?php
namespace iiif\model\resources;

use iiif\model\properties\ViewingDirectionTrait;

class Layer extends AbstractIiifResource
{
    use ViewingDirectionTrait;
    
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray)
    {
        // TODO Auto-generated method stub
        
    }

    
}

