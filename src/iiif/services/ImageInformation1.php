<?php
namespace iiif\services;

class ImageInformation1 extends AbstractImageService
{
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
        return "native";
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
        return "full";
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

