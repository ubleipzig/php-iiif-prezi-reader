<?php
namespace iiif\presentation\v3\model\resources;

class Range3 extends AbstractIiifResource3 {

    /**
     *
     * @var (Range3|Canvas3|SpecificResource3)[]
     */
    protected $items;

    /**
     *
     * @var Annotation3[]
     */
    protected $annotations;

    /**
     *
     * @var Canvas3
     */
    protected $posterCanvas;

    /**
     *
     * @var string
     */
    protected $navDate;

    /**
     *
     * @var string
     */
    protected $viewingDirection;

    /**
     *
     * @var (Canvas3|SpecificResource3)
     */
    protected $start;

    /**
     *
     * @var AnnotationCollection3
     */
    protected $supplementary;

    /**
     *
     * @return multitype:Ambigous <\iiif\presentation\v3\model\resources\Range3, \iiif\presentation\v3\model\resources\Canvas3, \iiif\presentation\v3\model\resources\SpecificResource3>
     */
    public function getItems() {
        return $this->items;
    }

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\Annotation3
     */
    public function getAnnotations() {
        return $this->annotations;
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\Canvas3
     */
    public function getPosterCanvas() {
        return $this->posterCanvas;
    }

    /**
     *
     * @return string
     */
    public function getNavDate() {
        return $this->navDate;
    }

    /**
     *
     * @return string
     */
    public function getViewingDirection() {
        return $this->viewingDirection;
    }

    /**
     *
     * @return (\iiif\presentation\v3\model\resources\Canvas3|\iiif\presentation\v3\model\resources\SpecificResource3)
     */
    public function getStart() {
        return $this->start;
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\AnnotationCollection3
     */
    public function getSupplementary() {
        return $this->supplementary;
    }

    public function getAllCanvases() {
        $allCanvases = [];
        if (isset($this->items) && sizeof($this->items) > 0) {
            foreach ($this->items as $item) {
                if ($item instanceof Canvas3) {
                    $allCanvases[] = $item;
                }
                if ($item instanceof Range3) {
                    $allCanvases = array_merge($allCanvases, $item->getAllCanvases());
                }
            }
        }
        return $allCanvases;
    }
}

