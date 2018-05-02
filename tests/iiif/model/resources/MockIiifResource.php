<?php
namespace iiif\model\resources;

class MockIiifResource extends AbstractIiifResource
{
    /**
     * Only for testing AbstractIiifResource methods
     * 
     * @param array $jsonAsArray
     * @param array $allResources
     * @return \iiif\model\resources\MockIiifResource
     */
    public static function fromArray($jsonAsArray, &$allResources = array())
    {
        return self::loadPropertiesFromArray($jsonAsArray, $allResources);
    }
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }
    public function setLabel($label)
    {
        $this->label = $label;
    }
}

