<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\StartCanvasTrait;
use iiif\presentation\v2\model\properties\ViewingDirectionTrait;
use iiif\presentation\common\model\resources\RangeInterface;
use iiif\presentation\v2\model\constants\ViewingHintValues;

class Range extends AbstractIiifResource implements RangeInterface {
    use ViewingDirectionTrait;
    use StartCanvasTrait;

    const TYPE = "sc:Range";

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

    /**
     * 
     * @var (Canvas|Range)[]
     */
    protected $members = array();

    protected function getStringResources() {
        return [
            "ranges" => Range::class,
            "canvases" => Canvas::class,
            "startCanvas" => Canvas::class
        ];
    }

    /**
     *
     * @return multitype:\iiif\model\resources\Range
     */
    public function getRanges() {
        return $this->ranges;
    }

    /**
     *
     * @return multitype:\iiif\model\resources\Canvas
     */
    public function getCanvases() {
        return $this->canvases;
    }

    /**
     * @return (Canvas|Range)[]
     */
    public function getMembers() {
        return $this->members;
    }

    public function getStartCanvasOrFirstCanvas() {
        if (isset($this->startCanvas)) {
            return $this->startCanvas;
        } elseif (isset($this->canvases) && sizeof($this->canvases) > 0) {
            return $this->canvases[0];
        } elseif (isset($this->ranges) && sizeof($this->ranges) > 0) {
            return $this->ranges[0]->getStartCanvasOrFirstCanvas();
        } elseif (isset($this->members) && sizeof($this->members) > 0) {
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

    /**
     * 
     * @return Canvas[]
     */
    public function getAllCanvasesRecursively() {
        $allCanvases = [];
        if (isset($this->canvases) && sizeof($this->canvases) > 0) {
            $allCanvases = $this->canvases;
        }
        if (isset($this->ranges) && sizeof($this->ranges) > 0) {
            foreach ($this->ranges as $range)
                $allCanvases = array_merge($allCanvases, $range->getAllCanvases());
        }
        if (isset($this->members) && sizeof($this->members) > 0) {
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
    
    public function getMemberRangesAndRanges() {
        $result = [];
        if (!empty($this->ranges)) {
            $result = $this->ranges;
        }
        if (!empty($this->members)) {
            foreach ($this->members as $member) {
                if ($member instanceof Range) {
                    $result[] = $member;
                }
            }
        }
        return $result;
    }
    
    public function getAllRanges() {
        return $this->getMemberRangesAndRanges();
    }
    
    public function getAllCanvases() {
        $result = [];
        if (!empty($this->canvases)) {
            $result = $this->canvases;
        }
        if (!empty($this->members)) {
            foreach ($this->members as $member) {
                if ($member instanceof Canvas) {
                    $result[] = $canvas;
                }
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllItems()
     */
    public function getAllItems() {
        $items = [];
        if ($this->getRanges()!=null) {
            foreach ($this->getRanges() as $range) {
                $items[] = $range;
            }
        }
        if ($this->getCanvases()!=null) {
            foreach ($this->getCanvases() as $canvas) {
                $items[] = $canvas;
            }
        }
        if ($this->getMembers()!=null) {
            foreach ($this->getMembers() as $member) {
                $items[] = $member;
            }
        }
        return $items;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        if ($this->getThumbnail()!=null) {
            parent::getThumbnailUrl();
            if ($this->getThumbnail() )
            if (is_string($this->getThumbnail())) {
                return $this->getThumbnail();
            }
        } else {
            $start = $this->getStartCanvasOrFirstCanvas();
            if ($start!=null) {
                return $start->getThumbnailUrl();
            }
        }
        return null;
    }
    
    public function isTopRange() {
        return isset($this->viewingHint) && $this->viewingHint == ViewingHintValues::TOP;
    }
}

