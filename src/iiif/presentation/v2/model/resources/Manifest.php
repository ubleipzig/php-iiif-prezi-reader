<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\NavDateTrait;
use iiif\presentation\v2\model\properties\ViewingDirectionTrait;
use iiif\presentation\v2\model\vocabulary\Names;
use iiif\presentation\v2\model\constants\ViewingDirectionValues;
use iiif\presentation\v2\model\constants\ViewingHintValues;

class Manifest extends AbstractIiifResource
{
    use NavDateTrait;
    use ViewingDirectionTrait;
    
    const CONTEXT="http://iiif.io/api/presentation/2/context.json";
    const TYPE="sc:Manifest";
    
    /**
     * 
     * @var Sequence[]
     */
    protected $sequences = array();
    
    protected $viewingDirection;
    protected $navDate;
    
    /**
     * 
     * @var Range[]
     */
    protected $structures = array();
    
    /**
     * The top structures in the hierarchy. Either a single range with the viewingHint property set to "top" or every range that is not contained in another range
     * @var Range[]
     */
    protected $topRanges;
    
    /**
     * 
     * @var AbstractIiifResource[]
     */
    protected $containedResources = array();
    
    public static function fromArray($jsonAsArray, &$allResources = array())
    {
        $manifest = self::createInstanceFromArray($jsonAsArray, $allResources);
        $manifest->loadPropertiesFromArray($jsonAsArray, $allResources);
        /* @var $manifest Manifest */
        $manifest->containedResources=&$allResources;
        $manifest->loadResources($jsonAsArray, Names::SEQUENCES, Sequence::class, $manifest->sequences, $manifest->containedResources);
        $manifest->loadResources($jsonAsArray, Names::STRUCTURES, Range::class, $manifest->structures, $manifest->containedResources);
        $manifest->navDate = array_key_exists(Names::NAV_DATE, $jsonAsArray) ? $jsonAsArray[Names::NAV_DATE] : null;
        $manifest->setViewingDirection(array_key_exists(Names::VIEWING_DIRECTION, $jsonAsArray) ? $jsonAsArray[Names::VIEWING_DIRECTION] : null);
        return $manifest;
    }
    /**
     * @return Sequence[]:
     */
    public function getSequences()
    {
        return $this->sequences;
    }
    /**
     * @return multitype:\iiif\model\resources\Range 
     */
    public function getStructures()
    {
        return $this->structures;
    }
    
    /**
     * Top structure in hierarchy; either the Range marked with viewingHint=top or the one that is not part of another range
     * @return Range
     */
    public function getTopRanges()
    {
        if ($this->topRanges == null) {
            $this->topRanges = array();
            foreach ($this->structures as $range) {
                $ranges[] = $range->getId();
            }
            foreach ($this->structures as $range) {
                if ($range->getViewingHint() == ViewingHintValues::TOP) {
                    // if there is a top structure, use it!
                    $this->topRanges[] = $range;
                    break;
                }
                foreach ($this->structures as $r) {
                    if (in_array($range, $r->getRanges())) {
                        $key = array_search($range->getId(), $ranges);
                        unset($ranges[$key]);
                    }
                }
            }
            if (sizeof($ranges) > 0) {
                foreach ($ranges as $rangeId)
                {
                    $this->topRanges[] = $this->getContainedResourceById($rangeId);
                }
            }
        }
        return $this->topRanges;
    }
    
    public function getContainedResourceById($id)
    {
        if (array_key_exists($id, $this->containedResources)) return $this->containedResources[$id];
    }
}


