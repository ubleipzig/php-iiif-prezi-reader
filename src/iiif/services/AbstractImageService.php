<?php
namespace iiif\services;



abstract class AbstractImageService extends Service {

    protected $width;

    protected $height;
    
    protected $formats = [];
    
    protected $qualities = [];
    
    protected $supports = [];
    
    protected $profileInitialized = false;
    
    protected abstract function getFullRegion();

    protected abstract function getMaxSize();

    protected abstract function getNoRotation();

    protected abstract function getDefaultQuality();

    protected abstract function getDefaultFormat();
    
    protected abstract function initializeProfile();

    public function getFormats() {
        $this->initializeProfile();
        return $this->formats;
    }
    
    public function getQualities() {
        $this->initializeProfile();
        return $this->qualities;
    }
    
    public function getSupports() {
        $this->initializeProfile();
        return $this->supports;
    }
    
    public function isFeatureSupported($feature) {
        if ($this->profile == null) {
            return false;
        }
        $this->initializeProfile();
        return array_search($feature, $this->supports) !== false;
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

