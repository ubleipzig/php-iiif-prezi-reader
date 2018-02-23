<?php
namespace iiif\model\resources;

use iiif\model\properties\NavDateTrait;

class Collection extends AbstractIiifResource
{
    use NavDateTrait;
    
    const TYPE="sc:Collection";
    
    protected $navDate;
    
    // deprecated
    protected $collections;
    // deprecated
    protected $manifests; 
    protected $members;
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    protected static function fromArray($jsonAsArray)
    {
        // TODO Auto-generated method stub
        
    }

}

