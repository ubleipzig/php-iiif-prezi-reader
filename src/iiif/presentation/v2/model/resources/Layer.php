<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\ViewingDirectionTrait;

class Layer extends AbstractIiifResource
{
    use ViewingDirectionTrait;
    
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v2\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources=array())
    {
        // TODO Auto-generated method stub
        
    }

    
}

