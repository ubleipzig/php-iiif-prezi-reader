<?php
namespace iiif\model\resources;

use iiif\model\properties\WidthAndHeightTrait;
use iiif\model\vocabulary\Names;

class Canvas extends AbstractIiifResource
{
    use WidthAndHeightTrait;
    
    CONST TYPE="sc:Canvas";
    
    protected $height;
    protected $width;
    protected $images = array();
    protected $otherContent = array();
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray)
    {
        $canvas = new Canvas();
        $canvas->loadPropertiesFromArray($jsonAsArray);
        $canvas->loadResources($jsonAsArray, Names::IMAGES, Annotation::class, $canvas->images);
        $canvas->loadResources($jsonAsArray, Names::OTHER_CONTENT, AnnotationList::class, $canvas->otherContent);
        return $canvas;
    }
}

