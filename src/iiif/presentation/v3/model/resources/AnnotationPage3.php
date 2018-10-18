<?php
namespace iiif\presentation\v3\model\resources;

class AnnotationPage3 extends AbstractIiifResource3 {

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
}

