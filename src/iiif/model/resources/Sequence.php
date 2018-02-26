<?php
namespace iiif\model\resources;

include_once 'AbstractIiifResource.php';


use iiif\model\properties\ViewingDirectionTrait;
use iiif\model\vocabulary\Names;

class Sequence extends AbstractIiifResource
{
    use ViewingDirectionTrait;
    
    const TYPE="sc:Sequence";
    
    protected $canvases = array();
    
    protected $startCanvas;
    protected $viewingDirection;
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray)
    {
        $sequence = new Sequence();
        $sequence->loadPropertiesFromArray($jsonAsArray);
        $sequence->loadResources($jsonAsArray, Names::CANVASES, Canvas::class, $sequence->canvases);
    }
}

