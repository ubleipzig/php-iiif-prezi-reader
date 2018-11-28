<?php
namespace iiif\presentation\common\model\resources;

interface CanvasInterface extends IiifResourceInterface {
    
    /**
     * @return AnnotationInterface[]
     */
    public function getImageAnnotations();
    
    /**
     * @return int
     */
    public function getWidth();
    
    /**
     * @return int
     */
    public function getHeight();
}

