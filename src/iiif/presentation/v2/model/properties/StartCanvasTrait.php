<?php
namespace iiif\presentation\v2\model\properties;


trait StartCanvasTrait {

    /**
     *
     * @var \iiif\presentation\v2\model\resources\Canvas
     */
    protected $startCanvas;

    public function getStartCanvas() {
        return $this->startCanvas;
    }

}

