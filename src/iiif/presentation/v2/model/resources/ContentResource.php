<?php
namespace iiif\presentation\v2\model\resources;

use iiif\services\AbstractImageService;
use iiif\presentation\v2\model\properties\WidthAndHeightTrait;

class ContentResource extends AbstractIiifResource {
    use WidthAndHeightTrait;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $chars;

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
    public function getChars() {
        return $this->chars;
    }

    public function getImageUrl() {
        $service = $this->service;
        if ($service instanceof AbstractImageService) {
            return $service->getImageUrl();
        }
    }
}

