<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\vocabulary\Names;
use iiif\presentation\v2\model\properties\WidthAndHeightTrait;

class Thumbnail
{
    use WidthAndHeightTrait;
    /**
     * 
     * @var string
     */
    protected $id;
    /**
     * 
     * @var Service
     */
    protected $service;
    
    public static function fromArray($jsonAsArray)
    {
        $thumbnail = new Thumbnail();
        $thumbnail->id = array_key_exists(Names::ID, $jsonAsArray) ? $jsonAsArray[Names::ID] : null;
        $thumbnail->service = array_key_exists(Names::SERVICE, $jsonAsArray) ? Service::fromArray($jsonAsArray[Names::SERVICE]) : null;
        $thumbnail->setWidthAndHeightFromJsonArray($jsonAsArray);
        
        return $thumbnail;
    }
    public function getImageUrl()
    {
        // TODO check if service provides IIIF image api
        return $this->id;
    }
}

