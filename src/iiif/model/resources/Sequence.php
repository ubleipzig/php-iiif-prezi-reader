<?php
namespace iiif\model\resources;

include_once 'AbstractIiifResource.php';


use iiif\model\properties\ViewingDirectionTrait;
use iiif\model\vocabulary\Names;
use iiif\model\properties\StartCanvasTrait;

class Sequence extends AbstractIiifResource
{
    use ViewingDirectionTrait;
    use StartCanvasTrait;
    
    const TYPE="sc:Sequence";
    
    /**
     * 
     * @var Canvas[]
     */
    protected $canvases = array();
    
    protected $viewingDirection;
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources=array())
    {
        $sequence = self::loadPropertiesFromArray($jsonAsArray, $allResources);
        /* @var $sequence Sequence */
        $sequence->loadResources($jsonAsArray, Names::CANVASES, Canvas::class, $sequence->canvases, $allResources);
        $sequence->loadStartCanvasFromJson($jsonAsArray, $allResources);
        return $sequence;
    }
    /**
     * @return multitype:\iiif\model\resources\Canvas 
     */
    public function getCanvases()
    {
        return $this->canvases;
    }
    public function getStartCanvasOrFirstCanvas()
    {
        if (isset($this->startCanvas)) {
            return $this->startCanvas;
        } elseif (isset($this->canvases) && sizeof($this->canvases)>0) {
            return $this->canvases[0];
        }
    }
}

