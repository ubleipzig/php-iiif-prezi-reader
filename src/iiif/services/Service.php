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
     * @var string|array
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
     * @return string|array
     */
    public function getProfile() {
        return $this->profile;
    }
    
    public function __construct($id = null, $profile = null) {
        $this->id = $id;
        $this->profile = $profile;
    }
}

