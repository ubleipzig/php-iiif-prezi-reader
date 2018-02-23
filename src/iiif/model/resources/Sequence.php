<?php
namespace iiif\model\resources;

use iiif\model\properties\ViewingDirectionTrait;

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
        if (array_key_exists("canvases", $jsonAsArray)) {
            $canvasesAsArray = $jsonAsArray["canvases"];
            foreach ($canvasesAsArray as $canvasAsArray)
            {
                $canvas = Canvas::fromArray($canvasAsArray);
                $this->canvases[] = $canvas;
            }
        }
    }
}

