<?php
namespace iiif\presentation\v1\model\resources;

use iiif\presentation\common\model\resources\RangeInterface;

class Range1 extends AbstractDescribableResource1 implements RangeInterface {

    /**
     * 
     * @var Canvas1[]
     */
    protected $canvases;
    
    /**
     * 
     * @var Range1|Manifest1
     */
    protected $within;
    
    protected $childRanges = [];
    
    protected $treeHierarchyInitialized = false;
    
    protected function getStringResources() {
        return [
            "within" => Range1::class,
            "canvases" => Canvas1::class
        ];
    }

    public function initTreeHierarchy() {
        if ($this->treeHierarchyInitialized) {
            return;
        }
        if ($this->within != null && $this->within instanceof Range1) {
            $this->within->childRanges[] = &$this;
        }
        $this->treeHierarchyInitialized = true;
    }
    
    /**
     * @return multitype:\iiif\presentation\v1\model\resources\Canvas1 
     */
    public function getCanvases() {
        return $this->canvases;
    }

    /**
     * @return Range1|Manifest1
     */
    public function getWithin() {
        return $this->within;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllCanvases()
     */
    public function getAllCanvases() {
        return $this->canvases;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllCanvasesRecursively()
     */
    public function getAllCanvasesRecursively() {
        $result = $this->getCanvases();
        if ($result == null) {
            $result = [];
        }
        if (!empty($this->getAllRanges())) {
            foreach ($this->getAllRanges() as $range) {
                $childCanvases = $range->getAllCanvasesRecursively();
                if (!empty($childCanvases)) {
                    foreach ($childCanvases as &$childCanvas) {
                        if (array_search($childCanvas, $result) === false) {
                            $result[] = &$childCanvas;
                        }
                    }
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
        return $items = array_merge($this->getCanvases() == null ? [] : $this->getCanvases(), $this->childRanges == null ? [] : $this->childRanges);
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllRanges()
     */
    public function getAllRanges() {
        return $this->childRanges;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getStartCanvas()
     */
    public function getStartCanvas() {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getStartCanvasOrFirstCanvas()
     */
    public function getStartCanvasOrFirstCanvas() {
        $canvases = $this->getAllCanvasesRecursively();
        return empty($canvases) ? null : $canvases[0];
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::isTopRange()
     */
    public function isTopRange() {
        return false;
    }
    
}

