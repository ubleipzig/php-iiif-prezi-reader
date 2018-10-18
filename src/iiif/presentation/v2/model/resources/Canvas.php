<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\WidthAndHeightTrait;

class Canvas extends AbstractIiifResource {
    use WidthAndHeightTrait;

    const TYPE = "sc:Canvas";

    /**
     *
     * @var Annotation[]
     */
    protected $images = array();

    /**
     *
     * @var AnnotationList[]
     */
    protected $otherContent = array();

    /**
     *
     * @return Annotation[]:
     */
    public function getImages() {
        return $this->images;
    }

    /**
     *
     * @return AnnotationList[]:
     */
    public function getOtherContent() {
        return $this->otherContent;
    }

    public function __construct($id = null, $reference = false) {
        if ($id !== null) {
            $this->id = $id;
        }
        if ($reference !== null) {
            $this->reference = $reference;
        }
    }
}

