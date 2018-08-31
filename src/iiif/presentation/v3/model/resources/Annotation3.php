<?php
namespace iiif\presentation\v3\model\resources;

class Annotation3 extends AbstractIiifResource3
{
    protected $timeMode;
    protected $motivation;
    protected $target;
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v3\model\resources\AbstractIiifEntity::loadProperty()
     */
    protected function loadProperty($term, $value, \iiif\context\JsonLdContext $context, array &$allResources = array())
    {
        if ($term == "target" && is_string($value)) {
            if (array_key_exists($value, $allResources)) {
                $this->target = $allResources[$value];
            } else {
                $resource = new Canvas3();
                $resource->id = $value;
                $allResources[$value] = $resource;
                $this->target = $resource;
            }
        }
        else {
            parent::loadProperty($term, $value, $context);
        }
    }
    
}

