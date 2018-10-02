<?php
namespace iiif\services;

class ImageInformation3 extends AbstractImageService
{
    CONST LEVEL1 = [""];
    
    /**
     * {@inheritDoc}
     * @see \iiif\services\AbstractImageService::getDefaultFormat()
     */
    protected function getDefaultFormat()
    {
        return "jpg";
    }

    /**
     * {@inheritDoc}
     * @see \iiif\services\AbstractImageService::getDefaultQuality()
     */
    protected function getDefaultQuality()
    {
        return "default";
    }

    /**
     * {@inheritDoc}
     * @see \iiif\services\AbstractImageService::getFullRegion()
     */
    protected function getFullRegion()
    {
        return "full";
    }

    /**
     * {@inheritDoc}
     * @see \iiif\services\AbstractImageService::getMaxSize()
     */
    protected function getMaxSize()
    {
        return "max";
    }

    /**
     * {@inheritDoc}
     * @see \iiif\services\AbstractImageService::getNoRotation()
     */
    protected function getNoRotation()
    {
        return "0";
    }

    
}

