<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\WidthAndHeightTrait;
use iiif\presentation\common\model\resources\CanvasInterface;

class Canvas extends AbstractIiifResource implements CanvasInterface {
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

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CanvasInterface::getImageAnnotations()
     */
    public function getImageAnnotations() {
        return $this->images;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result = parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        if (!empty($this->images)) {
            return $this->images[0]->getThumbnailUrl();
        }
        return null;
    }

}

