<?php
namespace iiif\presentation\v2\model\resources;

class MockIiifResource extends AbstractIiifResource
{
    /**
     * Only for testing AbstractIiifResource methods
     * 
     * @param array $jsonAsArray
     * @param array $allResources
     * @return \iiif\presentation\v2\model\resources\MockIiifResource
     */
    public static function fromArray($jsonAsArray, &$allResources = array())
    {
        $instance = self::createInstanceFromArray($jsonAsArray, $allResources);
        $instance->loadPropertiesFromArray($jsonAsArray, $allResources);
        return $instance;
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

