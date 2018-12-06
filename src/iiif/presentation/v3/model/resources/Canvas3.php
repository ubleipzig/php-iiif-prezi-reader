<?php
namespace iiif\presentation\v3\model\resources;

use iiif\presentation\v2\model\vocabulary\Motivation;
use iiif\presentation\common\model\resources\CanvasInterface;

class Canvas3 extends AbstractIiifResource3 implements CanvasInterface {

    /**
     *
     * @var AnnotationPage3[]
     */
    protected $items;

    /**
     *
     * @var AnnotationPage3[]
     */
    protected $annotations;

    /**
     *
     * @var Canvas3
     */
    protected $posterCanvas;

    /**
     *
     * @var string
     */
    protected $navDate;

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
     * @var float
     */
    protected $duration;

    /**
     *
     * @return \iiif\presentation\v3\model\resources\Annotation3[]
     */
    public function getImageAnnotationsForDisplay() {
        $imageAnnotations = [];
        foreach ($this->getItems() as $annotationPage) {
            foreach ($annotationPage->getItems() as $annotation) {
                /* @var $annotation Annotation3 */
                if ($annotation->getMotivation() == "painting" && $annotation->getBody()->getType() == "Image") {
                    $imageAnnotations[] = $annotation;
                }
            }
        }
        return $imageAnnotations;
    }

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\AnnotationPage3
     */
    public function getItems() {
        return $this->items;
    }

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\AnnotationPage3
     */
    public function getAnnotations() {
        return $this->items;
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\Canvas3
     */
    public function getPosterCanvas() {
        return $this->posterCanvas;
    }

    /**
     *
     * @return string
     */
    public function getNavDate() {
        return $this->navDate;
    }

    /**
     *
     * @return number
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     *
     * @return number
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     *
     * @return number
     */
    public function getDuration() {
        return $this->duration;
    }
    
    public function getImageAnnotations() {
        $result = [];
        if (isset($this->items)) {
            foreach ($this->items as $annotationPage) {
                // TODO ensure to only use embeded annotations
                if (!empty($annotationPage->getItems())) {
                    foreach ($annotationPage->getItems() as $annotation) {
                        if ($annotation->getMotivation() == "painting" && $annotation->getBody()!=null && $annotation->getBody()->getType() == "Image") {
                            $result[] = $annotation;
                        }
                    }
                }
            }
        }
        return $result;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v3\model\resources\AbstractIiifResource3::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result= parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        $images = $this->getImageAnnotationsForDisplay();
        if (!empty($images)) {
            return $images[0]->getThumbnailUrl();
        }
        return null;
    }

    

}

