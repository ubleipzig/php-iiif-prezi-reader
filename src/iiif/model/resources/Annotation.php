<?php
namespace iiif\model\resources;

use iiif\model\vocabulary\Names;

class Annotation extends AbstractIiifResource
{
    const TYPE="oa:Annotation";
    
    protected $motivation;
    /**
     * 
     * @var ContentResource
     */
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
        $annotation->motivation = array_key_exists(Names::MOTIVATION, $jsonAsArray) ? $jsonAsArray[Names::MOTIVATION] : null;
        $annotation->on = array_key_exists(Names::ON, $jsonAsArray) ? $jsonAsArray[Names::ON] : null;
        $annotation->loadSingleResouce($jsonAsArray, Names::RESOURCE, ContentResource::class, $annotation->resource);
        return $annotation;
    }
}

