<?php
namespace iiif\presentation\v3\model\resources;

use iiif\services\AbstractImageService;

class ContentResource3 extends AbstractIiifResource3 {

    /**
     *
     * @var AnnotationPage3[]
     */
    protected $annotations;

    /**
     *
     * @var string
     */
    protected $language;

    /**
     *
     * @var string
     */
    protected $format;

    /**
     *
     * @var string
     */
    protected $profile;

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
     * @return multitype:\iiif\presentation\v3\model\resources\AnnotationPage3
     */
    public function getAnnotations() {
        return $this->annotations;
    }

    /**
     *
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     *
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     *
     * @return string
     */
    public function getProfile() {
        return $this->profile;
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

    public function getImageUrl($width = null, $height = null) {
        if (($services = $this->service) != null) {
            if (is_array($services)) {
                foreach ($services as $service) {
                    if ($service instanceof AbstractImageService) {
                        break;
                    }
                }
            } else {
                $service = $services;
            }
            if ($service instanceof AbstractImageService) {
                $size = "full";
                if ($width != null && $heigth != null) {
                    $size = $width . "," . $height;
                } elseif ($width != null) {
                    $size = $width . ",";
                } elseif ($height != null) {
                    $size = "," . $height;
                }
                return $service->getImageUrl(null, $size);
            }
        }
    }
}

