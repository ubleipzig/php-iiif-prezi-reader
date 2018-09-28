<?php
namespace iiif\services;

class ImageInformation3 extends AbstractImageService
{
    public function getImageUrl($region = "full", $size = "max", $rotation = 0, $quality = "default", $format = "jpg")
    {
        return $this->getImageUrlInternal($region, $size, $rotation, $quality, $format);
    }
}

