<?php
namespace iiif\services;

class ImageInformation3 extends AbstractImageService {

    protected $extraFeatures = [];

    protected $extraFormats = [];
    
    protected $extraQualities = [];
    
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
        return "default";
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
        return "max";
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
            if (isset($this->extraFeatures) && !empty($this->extraFeatures)) {
                $this->supports = $this->extraFeatures;
            }
            if (isset($this->extraFormats) && !empty($this->extraFormats)) {
                $this->formats = $this->extraFormats;
            }
            if (isset($this->extraQualities) && !empty($this->extraQualities)) {
                $this->qualities = $this->extraQualities;
            }
            if (isset($this->profile) && is_string($this->profile)) {
                $complianceProfile = Profile::getComplianceLevelProfile($this->profile);
                if (isset($complianceProfile)) {
                    $this->formats = array_unique(array_merge($this->formats, $complianceProfile["formats"]));
                    $this->qualities = array_unique(array_merge($this->qualities, $complianceProfile["qualities"]));
                    $this->supports = array_unique(array_merge($this->supports, $complianceProfile["supported"]));
                }
            }
            $this->profileInitialized = true;
        }
    }
    
}

