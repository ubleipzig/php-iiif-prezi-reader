<?php
namespace iiif\presentation\common\model\resources;

interface AnnotationContainerInterface extends IiifResourceInterface {
    
    /**
     * @return AnnotationInterface[]
     */
    public function getTextAnnotations($motivation = null);
    
}

