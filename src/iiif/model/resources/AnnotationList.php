<?php
namespace iiif\model\resources;

use iiif\model\vocabulary\Names;

class AnnotationList extends AbstractIiifResource
{
    const TYPE="sc:AnnotationList";
    
    /**
     * 
     * @var Annotation[]
     */
    protected $resources = array();
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources=array())
    {
        $annotationList = self::loadPropertiesFromArray($jsonAsArray, $allResources);
        $annotationList->loadResources($jsonAsArray, Names::RESOURCES, Annotation::class, $annotationList->resources, $allResources);
        return $annotationList;
    }
}

