<?php
namespace iiif\presentation\v3\model\resources;

class Annotation3 extends AbstractIiifResource3 {

    /**
     *
     * @var string
     */
    protected $timeMode;

    /**
     *
     * @var string
     */
    protected $motivation;

    /**
     *
     * @var (Canvas3|SpecificResource3)
     */
    protected $target;

    /**
     *
     * @var ContentResource3
     */
    protected $body;

    /**
     *
     * {@inheritdoc}
     * @see \iiif\presentation\common\model\AbstractIiifEntity::getStringResources()
     */
    protected function getStringResources() {
        return [
            "target" => Canvas3::class
        ];
    }

    /**
     *
     * @return string
     */
    public function getTimeMode() {
        return $this->timeMode;
    }

    /**
     *
     * @return string
     */
    public function getMotivation() {
        return $this->motivation;
    }

    /**
     *
     * @return (\iiif\presentation\v3\model\resources\Canvas3|\iiif\presentation\v3\model\resources\SpecificResource3)
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\ContentResource3
     */
    public function getBody() {
        return $this->body;
    }
}

