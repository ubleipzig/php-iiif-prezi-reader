<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\common\model\resources\ManifestInterface;
use iiif\presentation\v2\model\constants\ViewingHintValues;
use iiif\presentation\v2\model\properties\NavDateTrait;
use iiif\presentation\v2\model\properties\ViewingDirectionTrait;

class Manifest extends AbstractIiifResource2 implements ManifestInterface {
    use NavDateTrait;
    use ViewingDirectionTrait;

    const CONTEXT = "http://iiif.io/api/presentation/2/context.json";

    const TYPE = "sc:Manifest";

    /**
     *
     * @var Sequence[]
     */
    protected $sequences = array();

    /**
     *
     * @var Range[]
     */
    protected $structures = array();

    /**
     * The top structures in the hierarchy.
     * Either a single range with the viewingHint property set to "top" or every range that is not contained in another range
     *
     * @var Range[]
     */
    protected $rootRanges;

    /**
     *
     * @var AbstractIiifResource2[]
     */
    protected $containedResources = array();

    /**
     *
     * @return Sequence[]:
     */
    public function getSequences() {
        return $this->sequences;
    }

    /**
     *
     * @return multitype:\iiif\model\resources\Range
     */
    public function getStructures() {
        return $this->structures;
    }

    /**
     * Top structure in hierarchy; either the Range marked with viewingHint=top or the one that is not part of another range
     *
     * @return Range[]
     */
    public function getRootRanges() {
        // TODO untested
        if ($this->rootRanges == null) {
            $this->rootRanges = array();
            $ranges = [];
            foreach ($this->structures as $range) {
                $ranges[] = $range->getId();
            }
            foreach ($this->structures as $range) {
                if ($range->getViewingHint() == ViewingHintValues::TOP) {
                    // if there is a top structure, use it!
                    $this->rootRanges[] = $range;
                    break;
                }
                foreach ($this->structures as $r) {
                    if (in_array($range, $r->getRanges())) {
                        $key = array_search($range->getId(), $ranges);
                        unset($ranges[$key]);
                    }
                }
            }
            if (sizeof($ranges) > 0 && sizeof($this->rootRanges)==0) {
                foreach ($ranges as $rangeId) {
                    $this->rootRanges[] = $this->getContainedResourceById($rangeId);
                }
            }
        }
        return $this->rootRanges;
    }

    public function getContainedResourceById($id) {
        if (array_key_exists($id, $this->containedResources))
            return $this->containedResources[$id];
    }

    private function getDefaultSequence()
    {
        if (!empty($this->sequences)) {
            return $this->sequences[0];
        }
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getDefaultCanvases()
     */
    public function getDefaultCanvases() {
        if ($this->getDefaultSequence() != null) {
            return $this->getDefaultSequence()->getCanvases();
        }
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getStartCanvas()
     */
    public function getStartCanvas() {
        if ($this->getDefaultSequence() != null) {
            return $this->getDefaultSequence()->getStartCanvas();
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getStartCanvasOrFirstCanvas()
     */
    public function getStartCanvasOrFirstCanvas() {
        if ($this->getDefaultSequence() != null) {
            return $this->getDefaultSequence()->getStartCanvasOrFirstCanvas();
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result = parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        $defaultSequence = $this->getDefaultSequence();
        if ($defaultSequence != null) {
            return $defaultSequence->getThumbnailUrl();
        }
        return null;
    }

}


