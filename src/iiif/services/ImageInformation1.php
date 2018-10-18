<?php
namespace iiif\services;

class ImageInformation1 extends AbstractImageService {

    /**
     *
     * {@inheritdoc}
     * @see \iiif\services\AbstractImageService::getDefaultFormat()
     */
    protected function getDefaultFormat() {
        return "jpg";
    }

    /**
     *
     * {@inheritdoc}
     * @see \iiif\services\AbstractImageService::getDefaultQuality()
     */
    protected function getDefaultQuality() {
        return "native";
    }

    /**
     *
     * {@inheritdoc}
     * @see \iiif\services\AbstractImageService::getFullRegion()
     */
    protected function getFullRegion() {
        return "full";
    }

    /**
     *
     * {@inheritdoc}
     * @see \iiif\services\AbstractImageService::getMaxSize()
     */
    protected function getMaxSize() {
        return "full";
    }

    /**
     *
     * {@inheritdoc}
     * @see \iiif\services\AbstractImageService::getNoRotation()
     */
    protected function getNoRotation() {
        return "0";
    }
}

