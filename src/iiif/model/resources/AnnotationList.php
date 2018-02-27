<?php
namespace iiif\model\resources;

use iiif\model\vocabulary\Names;

class AnnotationList extends AbstractIiifResource
{
    const TYPE="sc:AnnotationList";
    
    /**
     * 
     * @var AnnotationList[]
     */
    protected $resources = array();
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray)
    {
        $annotationList = new AnnotationList();
        $annotationList->loadPropertiesFromArray($jsonAsArray);
        $annotationList->loadResources($jsonAsArray, Names::RESOURCES, Annotation::class, $annotationList->resources);
        return $annotationList;
    }
}

