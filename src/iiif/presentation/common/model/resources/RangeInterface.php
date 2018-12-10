<?php
namespace iiif\presentation\common\model\resources;

interface RangeInterface extends IiifResourceInterface {
    
    /**
     * version 2: startCanvas
     * version 3: "start" if "start" is a canvas; otherwise the canvas whose part is given as "start"
     * @return CanvasInterface
     */
    public function getStartCanvas();

    /**
     * @return CanvasInterface Result of getStartCanvas(); if this is null, the first canvas in the range
     */
    public function getStartCanvasOrFirstCanvas();
    
    /**
     * version 2: "ranges", "canvases" and "members" 
     * version 3: items
     * @return (RangeInterface|CanvasInterface)[]
     */
    public function getAllItems();

    /**
     * version 2: all ranges and all members of type Range
     * version 3: all items of type Range 
     * @return RangeInterface[]
     */
    public function getAllRanges();
    
    /**
     * version 2: "canvases" and all "members" of type Canvas
     * version 3: all items of type Canvas
     * @return CanvasInterface[]
     */
    public function getAllCanvases();

    /**
     * version 2: return canvases contained in range and child ranges recursively
     * version 3: return canvases contained in child items of type Range
     * @return CanvasInterface[]
     */
    public function getAllCanvasesRecursively();
    
    /**
     *@return bool Range is a version 2 range with viewingHint=top
     */
    public function isTopRange();
    
}

