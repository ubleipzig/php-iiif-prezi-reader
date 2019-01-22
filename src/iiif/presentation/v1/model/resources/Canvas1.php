<?php
namespace iiif\presentation\v1\model\resources;

use iiif\presentation\common\model\resources\CanvasInterface;

class Canvas1 extends AbstractDescribableResource1 implements CanvasInterface {

    /**
     * 
     * @var int
     */
    protected $height;
    
    /**
     * 
     * @var int
     */
    protected $width;
    
    /**
     * 
     * @var Annotation1[]
     */
    protected $images;

    /**
     *
     * @var Annotation1[]
     */
    protected $otherContent;
    /**
     * @return number
     */

    public function getHeight() {
        return $this->height;
    }

    /**
     * @return number
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @return multitype:\iiif\presentation\v1\model\resources\Annotation1 
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * @return multitype:\iiif\presentation\v1\model\resources\Annotation1 
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
     * @see \iiif\presentation\common\model\resources\CanvasInterface::getPossibleTextAnnotationContainers()
     */
    public function getPossibleTextAnnotationContainers() {
        return $this->otherContent;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v1\model\resources\AbstractIiifResource1::getThumbnailUrl()
     */

    public function getThumbnailUrl() {
        if (!empty($this->images)) {
            return $this->images[0]->getThumbnailUrl();
        }
        return null;
    }
    
}