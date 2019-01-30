<?php
namespace iiif\presentation\v2\model\properties;

trait WidthAndHeightTrait
{

    protected $width;

    protected $height;

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

