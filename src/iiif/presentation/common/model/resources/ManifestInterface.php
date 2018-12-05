<?php
namespace iiif\presentation\common\model\resources;

interface ManifestInterface extends IiifResourceInterface {
    
    /**
     * version 2: canvases of default sequence
     * version 3: items of the first range with "sequence" behaviour; otherwise items of manifest 
     * @return CanvasInterface[]
     */
    public function getDefaultCanvases();

    /**
     * version 2: first range marked as top or any ranges that are no children of other ranges
     * version 3: ...
     * @return RangeInterface[]
     */
    public function getRootRanges();
    
    /**
     * version 2: startCanvas
     * version 3: "start" if "start" is a canvas; otherwise the canvas whose part is given as "start"
     * @return CanvasInterface
     */
    public function getStartCanvas();
    
    /**
     * @return CanvasInterface value of getStartCanvas() or first canvas in getDefaultCanvases()
     */
    public function getStartCanvasOrFirstCanvas();
    
    /**
     * @return RangeInterface[]
     */
    public function getStructures();
    
    public function getContainedResourceById($id);
    
}

