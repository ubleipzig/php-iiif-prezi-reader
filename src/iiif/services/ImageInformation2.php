<?php
namespace iiif\services;

use iiif\context\JsonLdHelper;

class ImageInformation2 extends AbstractImageService {

    protected $attribution;
    
    protected $license;
    
    protected $logo;
    
    protected $protocol;
    
    protected $sizes;
    
    protected $tiles;
    
    protected function getCollectionProperties() {
        return ["profile"];
    }
    
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
                $this->profile = JsonLdHelper::isSequentialArray($this->profile) ? $this->profile : [$this->profile];
                foreach ($this->profile as $profileEntry) {
                    if (is_string($profileEntry)) {
                        $complianceProfile = Profile::getComplianceLevelProfile($profileEntry);
                        if (isset($complianceProfile)) {
                            $this->formats = array_unique(array_merge($this->formats, $complianceProfile["formats"]));
                            $this->qualities = array_unique(array_merge($this->qualities, $complianceProfile["qualities"]));
                            $this->supports = array_unique(array_merge($this->supports, $complianceProfile["supported"]));
                        }
                    } elseif (JsonLdHelper::isDictionary($profileEntry)) {
                        foreach ($profileEntry as $key => $value) {
                            switch ($key) {
                                case "formats":
                                    if ($value!=null) {
                                        $this->formats = array_unique(array_merge($this->formats, $value));
                                    }
                                    break;
                                case "qualities":
                                    if ($value!=null) {
                                        $this->qualities = array_unique(array_merge($this->qualities, $value));
                                    }
                                    break;
                                case "supports":
                                    if ($value!=null) {
                                        $this->supports = array_unique(array_merge($this->supports, $value));
                                    }
                                    break;
                            }
                        }
                    }
                }
            }
            $this->profileInitialized = true;
        }
    }

}

