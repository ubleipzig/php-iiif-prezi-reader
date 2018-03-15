<?php
namespace iiif\model\resources;

class MockIiifResource extends AbstractIiifResource
{
    public static function fromArray($jsonAsArray, &$allResources = array())
    {
        $resource = new MockIiifResource();
        $resource->loadPropertiesFromArray($jsonAsArray, $allResources);
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

