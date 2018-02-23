<?php
namespace iiif\model\resources;

use iiif\model\properties\ViewingDirectionTrait;

class Range extends AbstractIiifResource
{
    use ViewingDirectionTrait;
    
    const TYPE="sc:Range";
    
    protected $ranges;
    protected $canvases;
    protected $members;
    
    protected $startCanvas;
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    protected static function fromArray($jsonAsArray)
    {
        // TODO Auto-generated method stub
        
    }

}

