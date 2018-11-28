<?php
namespace iiif\presentation\common\model\resources;

interface AnnotationInterface extends IiifResourceInterface {
    
    /**
     * version 2: resource
     * version 3: body
     * @return ContentResourceInterface
     */
    public function getBody();
    
    /**
     * @return string
     */
    public function getMotivation();
}

