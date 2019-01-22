<?php
namespace iiif\presentation\v1\model\resources;


use iiif\presentation\common\model\resources\AnnotationInterface;

class Annotation1 extends AbstractIiifResource1 implements AnnotationInterface {

    /**
     * 
     * @var string
     */
    protected $motivation;

    /**
     * 
     * @var ContentResource1
     */
    protected $resource;

    /**
     * 
     * @var mixed
     */
    protected $on;
    /**
     * @return string
     */
    public function getMotivation() {
        return $this->motivation;
    }

    /**
     * @return \iiif\presentation\v1\model\resources\ContentResource1
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     * @return mixed
     */
    public function getOn() {
        return $this->on;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v1\model\resources\AbstractIiifResource1::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        if ($this->motivation == "sc:painting" && $this->resource!=null && $this->resource instanceof ContentResource1) {
            return $this->resource->getThumbnailUrl();
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\AnnotationInterface::getBody()
     */
    public function getBody() {
        return $this->resource;
    }
    
}

