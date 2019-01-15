<?php
namespace iiif\presentation\common\model\resources;

interface CanvasInterface extends IiifResourceInterface {
    
    /**
     * @return AnnotationInterface[] All embedded image annotations.
     */
    public function getImageAnnotations();
    
    /**
     * @return AnnotationContainerInterface[]
     */
    public function getPossibleTextAnnotationContainers();
    
    /**
     * @return int
     */
    public function getWidth();
    
    /**
     * @return int
     */
    public function getHeight();
}

