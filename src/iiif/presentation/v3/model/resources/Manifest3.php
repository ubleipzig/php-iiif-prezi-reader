<?php
namespace iiif\presentation\v3\model\resources;

class Manifest3 extends AbstractIiifResource3 {

    /**
     *
     * @var Canvas3[]
     */
    protected $items;

    /**
     *
     * @var Range3[]
     */
    protected $structures;

    /**
     *
     * @var Annotation3[];
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
     * @var Canvas3
     */
    protected $start;

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\Canvas3
     */
    public function getItems() {
        return $this->items;
    }

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\Range3
     */
    public function getStructures() {
        return $this->structures;
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\Annotation3[];
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
     * @return \iiif\presentation\v3\model\resources\Canvas3
     */
    public function getStart() {
        return $this->start;
    }
}

