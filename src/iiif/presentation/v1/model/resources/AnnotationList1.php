<?php
namespace iiif\presentation\v1\model\resources;


use iiif\presentation\common\model\resources\AnnotationContainerInterface;

class AnnotationList1 extends AbstractIiifResource1 implements AnnotationContainerInterface {
    
    /**
     * 
     * @var Annotation1[]
     */
    protected $resources;
    
    /**
     * @return multitype:\iiif\presentation\v1\model\resources\Annotation1 
     */
    public function getResources() {
        // TODO if the annotation list is only linked in the document, get the remote content
        return $this->resources;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\AnnotationContainerInterface::getTextAnnotations()
     */
    public function getTextAnnotations($motivation = null) {
        // TODO Auto-generated method stub
        
    }
    
}

