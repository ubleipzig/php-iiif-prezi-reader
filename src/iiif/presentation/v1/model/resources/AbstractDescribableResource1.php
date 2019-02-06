<?php
namespace iiif\presentation\v1\model\resources;

use iiif\context\JsonLdHelper;
use iiif\presentation\common\model\resources\IiifResourceInterface;

abstract class AbstractDescribableResource1 extends AbstractIiifResource1 {
    /**
     * 
     * @var array
     */
    protected $metadata;
    
    /**
     * 
     * @var string|array
     */
    protected $description;

    /**
     * @return array
     */
    public function getMetadata() {
        return $this->metadata;
    }

    /**
     * @return string|array
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v1\model\resources\AbstractIiifResource1::getMetadataForDisplay()
     */
    public function getMetadataForDisplay($language = null, $joinChars = "; ", $options = 0) {
        if (!isset($this->metadata) || !JsonLdHelper::isSequentialArray($this->metadata)) {
            return null;
        }
        $result = null;
        foreach ($this->metadata as $metadata) {
            $resultData = [];
            if (array_key_exists("label", $metadata)) {
                $resultData["label"] = $this->getValueForDisplay($metadata["label"], $language, $joinChars, false, IiifResourceInterface::SANITIZE_XML_ENCODE_ALL);
            } else {
                $resultData["label"] = "";
            }
            if (array_key_exists("value", $metadata)) {
                $resultData["value"] = $this->getValueForDisplay($metadata["value"], $language, $joinChars, true, $options);
            } else {
                $resultData["value"] = "";
            }
            $result[] = $resultData;
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v1\model\resources\AbstractIiifResource1::getSummary()
     */
    public function getSummary() {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v1\model\resources\AbstractIiifResource1::getSummaryForDisplay()
     */
    public function getSummaryForDisplay($language = null, $joinChars = "; ", $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML) {
        return $this->getValueForDisplay($this->description, $language, $joinChars);
    }
    
}

