<?php
namespace iiif\services;

class ImageInformation2 extends AbstractImageService
{
    /**
     * {@inheritDoc}
     * @see \iiif\services\AbstractImageService::getImageUrl()
     */
    public function getImageUrl($region = "full", $size = "full", $rotation = 0, $quality = "default", $format = "jpg")
    {
        return $this->getImageUrlInternal($region, $size, $rotation, $quality, $format);
    }

    
}

