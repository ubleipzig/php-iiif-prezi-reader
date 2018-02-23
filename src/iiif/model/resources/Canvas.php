<?php
namespace iiif\model\resources;

use iiif\model\properties\WidthAndHeightTrait;

class Canvas extends AbstractIiifResource
{
    use WidthAndHeightTrait;
    
    CONST TYPE="sc:Canvas";
    
    protected $height;
    protected $width;
    protected $images = array();
    protected $otherContent = array();
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    protected static function fromArray($jsonAsArray)
    {
        $canvas = new Canvas();
        $canvas->loadPropertiesFromArray($jsonAsArray);
        if (array_key_exists("images", $jsonAsArray))
        {
            $imagesAsArray = $jsonAsArray["images"];
            foreach ($imagesAsArray as $imageAsArray)
            {
                $image = Annotation::fromArray($imageAsArray);
                $this->images[] = $image;
            }
        }
    }
}

