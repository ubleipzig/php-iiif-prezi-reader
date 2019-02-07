<?php
namespace iiif\services;



abstract class AbstractImageService extends Service {

    /**
     * 
     * @var int
     */
    protected $width;

    /**
     *
     * @var int
     */
    protected $height;
    
    /**
     * 
     * @var array
     */
    protected $formats = [];
    
    /**
     *
     * @var array
     */
    protected $qualities = [];
    
    /**
     *
     * @var array
     */
    protected $supports = [];
    
    /**
     * 
     * @var boolean
     */
    protected $profileInitialized = false;
    
    protected $sizes;
    
    protected $tiles;
    
    /**
     * Region parameter URL part for the complete image.
     * @return string "full" for versions 1, 2 and 3
     */
    protected abstract function getFullRegion();

    /**
     * Size parameter URL part for the maximum supported size.
     * @return string
     * version 1: "full";
     * version 2: "max", "full" ("max" was not supported by IIPSrv until 12/2017 so we always serve "full" if "max" is not required);
     * version 3: "max"
     */
    protected abstract function getMaxSize();

    /**
     * Rotation parameter URL part with neither rotation nor mirroring.
     * @return string "0"
     */
    protected abstract function getNoRotation();

    /**
     * A quality that all IIIF image servers should be able to serve. 
     * @return string "native" for version 1, "default" for versions 2 and 3
     */
    protected abstract function getDefaultQuality();

    /**
     * @return string "jpg" which should always be supported.
     */
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


    /**
     * @return number
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @return number
     */
    public function getHeight() {
        return $this->height;
    }

    public abstract function getSizes();
    
    public function __construct($id = null, $profile = null, $width = null, $height = null, $sizes = null, $tiles = null) {
        $this->id = $id;
        $this->profile = $profile;
        $this->width = $width;
        $this->height = $height;
        $this->sizes = $sizes;
        $this->tiles = $tiles;
    }
}

