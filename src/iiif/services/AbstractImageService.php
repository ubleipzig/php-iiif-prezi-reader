<?php
namespace iiif\services;


abstract class AbstractImageService extends Service
{
    protected $width;
    protected $height;
    
    
    public abstract function getImageUrl($region = null, $size = null, $rotation = null, $quality = null, $format = null);
    
    protected function getImageUrlInternal($region, $size, $rotation, $quality, $format) {
        $baseUrl = strrpos($this->id, "/")  - strlen($this->id) + 1 === 0 ? $this->id : ($this->id."/");
        return $baseUrl.$region."/".$size."/".$rotation."/".$quality.".".$format;
    }
}

