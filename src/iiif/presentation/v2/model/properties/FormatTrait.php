<?php
namespace iiif\presentation\v2\model\properties;

trait FormatTrait
{

    protected $format;

    /**
     *
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }
}

