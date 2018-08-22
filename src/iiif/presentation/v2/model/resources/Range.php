<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\ViewingDirectionTrait;
use iiif\presentation\v2\model\vocabulary\Names;
use iiif\presentation\v2\model\vocabulary\Types;
use iiif\presentation\v2\model\vocabulary\MiscNames;
use iiif\presentation\v2\model\properties\StartCanvasTrait;

class Range extends AbstractIiifResource
{
    use ViewingDirectionTrait;
    use StartCanvasTrait;
    
    const TYPE="sc:Range";
    
    /**
     * 
     * @var Range[]
     */
    protected $ranges = array();
    /**
     * 
     * @var Canvas[]
     */
    protected $canvases = array();
    
    protected $members = array();
    
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v2\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources=array())
    {
        $range = self::createInstanceFromArray($jsonAsArray, $allResources);
        $range->loadPropertiesFromArray($jsonAsArray, $allResources);
        /* @var $range Range */
        $range->loadResources($jsonAsArray, Names::CANVASES, Canvas::class, $range->canvases, $allResources);
        $range->loadResources($jsonAsArray, Names::RANGES, Range::class, $range->ranges, $allResources);
        
        $memberRanges=array(Names::TYPE=>Types::SC_RANGE, MiscNames::CLAZZ=>Range::class);
        $memberCanvases=array(Names::TYPE=>Types::SC_CANVAS, MiscNames::CLAZZ=>Canvas::class);
        $range->loadMixedResources($jsonAsArray, Names::MEMBERS, array($memberRanges, $memberCanvases), $range->members, $allResources);
        $range->loadStartCanvasFromJson($jsonAsArray, $allResources);
        $range->setViewingDirection(array_key_exists(Names::VIEWING_DIRECTION, $jsonAsArray) ? $jsonAsArray[Names::VIEWING_DIRECTION] : null);
        
        return $range;
    }
    /**
     * @return multitype:\iiif\model\resources\Range 
     */
    public function getRanges()
    {
        return $this->ranges;
    }

    /**
     * @return multitype:\iiif\model\resources\Canvas 
     */
    public function getCanvases()
    {
        return $this->canvases;
    }

    /**
     * @return multitype:
     */
    public function getMembers()
    {
        return $this->members;
    }

    public function getStartCanvasOrFirstCanvas()
    {
        if (isset($this->startCanvas)) {
            return $this->startCanvas;
        } elseif (isset($this->canvases) && sizeof($this->canvases)>0) {
            return $this->canvases[0];
        } elseif (isset($this->ranges) && sizeof($this->ranges)>0) {
            return $this->ranges[0]->getStartCanvasOrFirstCanvas();
        } elseif (isset($this->members) && sizeof($this->members)>0) {
            foreach ($this->members as $member) {
                if ($member instanceof Canvas) {
                    return $member;
                } elseif ($member instanceof Range) {
                    return $member->getStartCanvasOrFirstCanvas();
                }
            }
        }
        return null;
    }
    
    public function getAllCanvases()
    {
        $allCanvases = [];
        if (isset($this->canvases) && sizeof($this->canvases)>0) {
            $allCanvases = $this->canvases;
        }
        if (isset($this->ranges) && sizeof($this->ranges)>0) {
            foreach ($this->ranges as $range)
            $allCanvases = array_merge($allCanvases, $range->getAllCanvases());
        }
        if (isset($this->members) && sizeof($this->members)>0) {
            foreach ($this->members as $member) {
                if ($member instanceof Canvas) {
                    $allCanvases[] = $member;
                }
                if ($member instanceof Range) {
                    $allCanvases = array_merge($allCanvases, $member->getAllCanvases());
                }
            }
        }
        return $allCanvases;
    }
    
}

