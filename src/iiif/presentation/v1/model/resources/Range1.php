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
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllCanvasesRecursively()
     */
    public function getAllCanvasesRecursively() {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllItems()
     */
    public function getAllItems() {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllRanges()
     */
    public function getAllRanges() {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getStartCanvas()
     */
    public function getStartCanvas() {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getStartCanvasOrFirstCanvas()
     */
    public function getStartCanvasOrFirstCanvas() {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::isTopRange()
     */
    public function isTopRange() {
        return $this->within == null || $this->within instanceof Manifest1;
    }
    
}

