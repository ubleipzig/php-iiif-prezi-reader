<?php
namespace iiif\presentation\v1\model\resources;


class Sequence1 extends AbstractDescribableResource1 {
 
    /**
     * 
     * @var Canvas1[]
     */
    protected $canvases;
    
    /**
     * 
     * @var string
     */
    protected $viewingDirection;
    
    /**
     *
     * @var string
     */
    protected $viewingHint;
    /**
     * @return multitype:\iiif\presentation\v1\model\resources\Canvas1 
     */
    public function getCanvases() {
        return $this->canvases;
    }

    /**
     * @return string
     */
    public function getViewingDirection() {
        return $this->viewingDirection;
    }

    /**
     * @return string
     */
    public function getViewingHint() {
        return $this->viewingHint;
    }
    
}

