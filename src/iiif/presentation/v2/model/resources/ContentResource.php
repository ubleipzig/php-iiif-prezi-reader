<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\vocabulary\Names;
use iiif\services\AbstractImageService;

class ContentResource extends AbstractIiifResource {

    protected $format;

    protected $chars;

    /**
     *
     * {@inheritdoc}
     * @see \iiif\presentation\v2\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources = array()) {
        $contentResource = self::createInstanceFromArray($jsonAsArray, $allResources);
        $contentResource->loadPropertiesFromArray($jsonAsArray, $allResources);
        $contentResource->format = array_key_exists(Names::FORMAT, $jsonAsArray) ? $jsonAsArray[Names::FORMAT] : null;
        $contentResource->chars = array_key_exists(Names::CHARS, $jsonAsArray) ? $jsonAsArray[Names::CHARS] : null;
        return $contentResource;
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

