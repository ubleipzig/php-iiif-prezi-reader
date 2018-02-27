<?php
namespace iiif\model\properties;

trait FormatTrait
{
    protected $format;
    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }
}

