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

    /**
     * {@inheritDoc}
     * @see \iiif\services\AbstractImageService::initializeProfile()
     */
    protected function initializeProfile() {
        if (!$this->profileInitialized) {
            if (isset($this->profile)) {
                $this->supports = Profile::getSupported($this->profile);
                $formatsByLevel = Profile::getFormats($this->profile);
                if (!empty($formatsByLevel)) {
                    $this->formats = isset($this->formats) ? array_unique(array_merge($this->formats, $formatsByLevel)) : $formatsByLevel;
                }
                $qualitiesByLevel = Profile::getQualities($this->profile);
                if (!empty($qualitiesByLevel)) {
                    $this->qualities = isset($this->qualities) ? array_unique(array_merge($this->qualities, $qualitiesByLevel)) : $qualitiesByLevel;
                }
            }
            $this->profileInitialized = true;
        }
    }
    /**
     * {@inheritDoc}
     * @see \iiif\services\AbstractImageService::getSizes()
     */
    public function getSizes() {
        return null;
    }

}

