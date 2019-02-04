<?php
namespace iiif\presentation\common\model\resources;


interface AnnotationContainerInterface extends IiifResourceInterface {
    
    /**
     * @param $motivation string The name of an instance of https://www.w3.org/ns/oa#Motivation,
     * usually "painting", respectively "sc:painting", or "oa:commenting".
     * @return AnnotationInterface[] All text annotations with the given $motivation,
     * or all text annotations if $motivation is null.
     * @link https://www.w3.org/TR/annotation-vocab/#motivation
     * 
     */
    public function getTextAnnotations($motivation = null);
    
}

