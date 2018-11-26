<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\StartCanvasTrait;
use iiif\presentation\v2\model\properties\ViewingDirectionTrait;

class Range extends AbstractIiifResource {
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
     *
     * @return multitype:
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

    public function getAllCanvases() {
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
}

