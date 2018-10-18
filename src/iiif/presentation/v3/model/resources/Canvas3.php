<?php
namespace iiif\presentation\v3\model\resources;

use iiif\presentation\v2\model\vocabulary\Motivation;

class Canvas3 extends AbstractIiifResource3 {

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
                if ($annotation->getMotivation() == Motivation::PAINTING && $annotation->getBody()->getType() == "http://purl.org/dc/dcmitype/StillImage") {
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
        return $this->annotations;
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
}

