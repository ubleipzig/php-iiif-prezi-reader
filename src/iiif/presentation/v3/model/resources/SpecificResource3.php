<?php
namespace iiif\presentation\v3\model\resources;

use iiif\presentation\common\model\AbstractIiifEntity;

class SpecificResource3 extends AbstractIiifEntity {

    /**
     *
     * @var Canvas3
     */
    protected $source;

    /**
     *
     * @var mixed
     */
    protected $selector;

    /**
     *
     * {@inheritdoc}
     * @see \iiif\presentation\common\model\AbstractIiifEntity::getStringResources()
     */
    protected function getStringResources() {
        return [
            "source" => Canvas3::class
        ];
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\Canvas3
     */
    public function getSource() {
        return $this->source;
    }

    /**
     *
     * @return mixed
     */
    public function getSelector() {
        return $this->selector;
    }
}

