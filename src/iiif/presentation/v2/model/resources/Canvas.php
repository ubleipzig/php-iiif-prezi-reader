<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\WidthAndHeightTrait;
use iiif\presentation\v2\model\vocabulary\Names;

class Canvas extends AbstractIiifResource
{
    use WidthAndHeightTrait;
    
    CONST TYPE="sc:Canvas";
    
    /**
     * 
     * @var Annotation[]
     */
    protected $images = array();
    /**
     * 
     * @var AnnotationList[]
     */
    protected $otherContent = array();
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v2\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources=array())
    {
        $canvas = self::createInstanceFromArray($jsonAsArray, $allResources);
        $canvas->loadPropertiesFromArray($jsonAsArray, $allResources);
        $canvas->loadResources($jsonAsArray, Names::IMAGES, Annotation::class, $canvas->images, $allResources);
        $canvas->loadResources($jsonAsArray, Names::OTHER_CONTENT, AnnotationList::class, $canvas->otherContent, $allResources);
        $canvas->setWidthAndHeightFromJsonArray($jsonAsArray);
        return $canvas;
    }
    /**
     * @return Annotation[]:
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return AnnotationList[]:
     */
    public function getOtherContent()
    {
        return $this->otherContent;
    }
    
    public function __construct($id = null, $reference = false)
    {
        if ($id !== null) {
            $this->id = $id;
        }
        if ($reference !== null) {
            $this->reference = $reference;
        }
    }

}

