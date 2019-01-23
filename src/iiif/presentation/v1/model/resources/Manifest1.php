<?php
namespace iiif\presentation\v1\model\resources;

use iiif\presentation\common\model\resources\ManifestInterface;

class Manifest1 extends AbstractDescribableResource1 implements ManifestInterface {
    
    /**
     * 
     * @var Sequence1[]
     */
    protected $sequences;
    
    /**
     * 
     * @var Range1[]
     */
    protected $structures;
    
    protected $treeHierarchyInitialized = false;

    /**
     * @return multitype:\iiif\presentation\v1\model\resources\Sequence1 
     */
    public function getSequences() {
        return $this->sequences;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getStructures()
     */
    public function getStructures() {
        $this->structures;
    }
    
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getContainedResourceById()
     */
    public function getContainedResourceById($id) {
        if ($this->containedResources != null && array_key_exists($id, $this->containedResources)) {
            return $this->containedResources[$id];
        }
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getDefaultCanvases()
     */
    public function getDefaultCanvases() {
        if (!empty($this->sequences)) {
            return $this->sequences[0]->getCanvases();
        }
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getRootRanges()
     */
    public function getRootRanges() {
        
        if (!$this->treeHierarchyInitialized) {
            
            if (!empty($this->structures)) {
                foreach ($this->structures as $range) {
                    $range->initTreeHierarchy();
                }
            }
            
            $this->treeHierarchyInitialized = true;
        }
        
        // TODO Auto-generated method stub
        
    }

    /**
     * Metadata API has no property like "start" (http://iiif.io/api/presentation/3#start) or "startCanvas" (http://iiif.io/api/presentation/2#hasStartCanvas)
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getStartCanvas()
     */
    public function getStartCanvas() {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getStartCanvasOrFirstCanvas()
     */
    public function getStartCanvasOrFirstCanvas() {
        if (!empty($this->getDefaultCanvases())) {
            return $this->getDefaultCanvases()[0];
        }
        return null;
    }

}