<?php
namespace iiif\model\properties;

use iiif\model\resources\Canvas;
use iiif\model\vocabulary\Names;

trait StartCanvasTrait {
    
    /**
     *
     * @var Canvas
     */
    protected $startCanvas;
    
    public function getStartCanvas()
    {
        return $this->startCanvas;
    }
    
    protected function loadStartCanvasFromJson($jsonAsArray, &$allResources)
    {
        if (array_key_exists(Names::START_CANVAS, $jsonAsArray) && $jsonAsArray[Names::START_CANVAS]!=null) {
            $canvasId=$jsonAsArray[Names::START_CANVAS];
            if (array_key_exists($canvasId, $allResources)) {
                $this->startCanvas = &$allResources[$canvasId];
            } else {
                $this->startCanvas = new Canvas($canvasId, true);
                $allResources[$canvasId] = &$this->startCanvas;
            }
        }
    }
}

