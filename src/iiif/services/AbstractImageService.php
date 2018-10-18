<?php
namespace iiif\services;

abstract class AbstractImageService extends Service {

    protected $width;

    protected $height;

    protected abstract function getFullRegion();

    protected abstract function getMaxSize();

    protected abstract function getNoRotation();

    protected abstract function getDefaultQuality();

    protected abstract function getDefaultFormat();

    public function isFeatureSupported($feature) {
        if ($this->profile == null) {
            return null;
        }
    }

    public function getImageUrl($region = null, $size = null, $rotation = null, $quality = null, $format = null) {
        $region = $region == null ? $this->getFullRegion() : $region;
        $size = $size == null ? $this->getMaxSize() : $size;
        $rotation = $rotation == null ? $this->getNoRotation() : $rotation;
        $quality = $quality == null ? $this->getDefaultQuality() : $quality;
        $format = $format == null ? $this->getDefaultFormat() : $format;
        
        $baseUrl = strrpos($this->id, "/") - strlen($this->id) + 1 === 0 ? $this->id : ($this->id . "/");
        return $baseUrl . $region . "/" . $size . "/" . $rotation . "/" . $quality . "." . $format;
    }
}

