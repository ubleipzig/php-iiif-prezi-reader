<?php
namespace iiif\presentation\v2\model\resources;

use iiif\services\AbstractImageService;

class ContentResource extends AbstractIiifResource {

    protected $format;

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

