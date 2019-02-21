<?php
namespace Ubl\Iiif\Presentation\V3\Model\Properties;

trait PlaceholderAndAccompanyingCanvasTrait {
    
    /**
     * @var \Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3
     */
    protected $placeholderCanvas;
    
    /**
     * @var \Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3
     */
    protected $accompanyingCanvas;

    /**
     * @return \Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3
     */
    public function getPlaceholderCanvas() {
        return $this->placeholderCanvas;
    }

    /**
     * @return \Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3
     */
    public function getAccompanyingCanvas() {
        return $this->accompanyingCanvas;
    }

}

