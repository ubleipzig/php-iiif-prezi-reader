<?php
namespace iiif\services;

use iiif\presentation\common\model\AbstractIiifEntity;

class Service extends AbstractIiifEntity {

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $profile;

    /**
     *
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getProfile() {
        return $this->profile;
    }
}

