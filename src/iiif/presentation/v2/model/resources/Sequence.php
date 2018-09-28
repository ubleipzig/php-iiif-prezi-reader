<?php
namespace iiif\presentation\v2\model\resources;

include_once 'AbstractIiifResource.php';


use iiif\presentation\v2\model\properties\ViewingDirectionTrait;
use iiif\presentation\v2\model\vocabulary\Names;
use iiif\presentation\v2\model\properties\StartCanvasTrait;

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
     * @see \iiif\presentation\common\model\AbstractIiifEntity::getStringResources()
     */
    protected function getStringResources()
    {
        return ["startCanvas"=>Canvas::class];
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v2\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources=array())
    {
        $sequence = self::createInstanceFromArray($jsonAsArray, $allResources);
        $sequence->loadPropertiesFromArray($jsonAsArray, $allResources);
        /* @var $sequence Sequence */
        $sequence->loadResources($jsonAsArray, Names::CANVASES, Canvas::class, $sequence->canvases, $allResources);
        $sequence->loadStartCanvasFromJson($jsonAsArray, $allResources);
        $sequence->setViewingDirection(array_key_exists(Names::VIEWING_DIRECTION, $jsonAsArray) ? $jsonAsArray[Names::VIEWING_DIRECTION] : null);
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

