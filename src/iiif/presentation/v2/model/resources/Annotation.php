<?php
namespace iiif\presentation\v2\model\resources;

class Annotation extends AbstractIiifResource {

    const TYPE = "oa:Annotation";

    protected $motivation;

    /**
     *
     * @var ContentResource
     */
    protected $resource;

    protected $on;

    /**
     *
     * @return \iiif\presentation\v2\model\resources\ContentResource
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     *
     * @return mixed
     */
    public function getOn() {
        return $this->on;
    }

    /**
     *
     * @return string
     */
    public function getMotivation() {
        return $this->motivation;
    }
}

