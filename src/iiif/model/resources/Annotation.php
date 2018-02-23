<?php
namespace iiif\model\resources;

use iiif\model\properties\FormatTrait;
use iiif\model\properties\WidthAndHeightTrait;

class Annotation extends AbstractIiifResource
{
    const TYPE="oa:Annotation";
    
    protected $motivation;
    protected $resource;
    protected $on;
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray)
    {
        $annotation = new Annotation();
        $annotation->loadPropertiesFromArray($jsonAsArray);
        $annotation->motivation = $jsonAsArray["motivation"];
        
    }

}

