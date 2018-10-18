<?php
namespace iiif\presentation\v2\model\properties;

use iiif\presentation\v2\model\vocabulary\Names;

trait WidthAndHeightTrait
{

    protected $width;

    protected $height;

    public function setWidthAndHeightFromJsonArray($jsonAsArray) {
        $this->width = array_key_exists(Names::WIDTH, $jsonAsArray) ? $jsonAsArray[Names::WIDTH] : null;
        $this->height = array_key_exists(Names::HEIGHT, $jsonAsArray) ? $jsonAsArray[Names::HEIGHT] : null;
    }

    /**
     *
     * @return int
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     *
     * @return int
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     *
     * @param int $width
     */
    public function setWidth($width) {
        $this->width = $width;
    }

    /**
     *
     * @param int $height
     */
    public function setHeight($height) {
        $this->height = $height;
    }
}

