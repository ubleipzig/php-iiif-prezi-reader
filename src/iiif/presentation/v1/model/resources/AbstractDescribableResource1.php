<?php
namespace iiif\presentation\v1\model\resources;

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
        // TODO Auto-generated method stub
        return null;
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
    public function getSummaryForDisplay($language = null, $joinChars = "; ") {
        return $this->getValueForDisplay($this->description, $language, $joinChars);
    }
    
}

