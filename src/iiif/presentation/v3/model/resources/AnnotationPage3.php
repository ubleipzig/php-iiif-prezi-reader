<?php
namespace iiif\presentation\v3\model\resources;

use iiif\presentation\common\model\resources\AnnotationContainerInterface;

class AnnotationPage3 extends AbstractIiifResource3 implements AnnotationContainerInterface {

    /**
     *
     * @var Annotation3[]
     */
    protected $items;

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\Annotation3
     */
    public function getItems() {
        return $this->items;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\AnnotationContainerInterface::getTextAnnotations()
     */
    public function getTextAnnotations($motivation = null) {
        // TODO Auto-generated method stub
        
    }



}

