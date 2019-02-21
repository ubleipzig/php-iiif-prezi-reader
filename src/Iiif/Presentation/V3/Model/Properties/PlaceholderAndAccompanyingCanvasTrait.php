<?php
namespace iiif\presentation\v3\model\properties;

trait PlaceholderAndAccompanyingCanvasTrait {
    
    /**
     * @var \iiif\presentation\v3\model\resources\Canvas3
     */
    protected $placeholderCanvas;
    
    /**
     * @var \iiif\presentation\v3\model\resources\Canvas3
     */
    protected $accompanyingCanvas;

    /**
     * @return \iiif\presentation\v3\model\resources\Canvas3
     */
    public function getPlaceholderCanvas() {
        return $this->placeholderCanvas;
    }

    /**
     * @return \iiif\presentation\v3\model\resources\Canvas3
     */
    public function getAccompanyingCanvas() {
        return $this->accompanyingCanvas;
    }

}

