<?php
namespace iiif\model\resources;

use iiif\model\vocabulary\Names;

class Thumbnail
{
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
    }
    public function getImageUrl()
    {
        // TODO check if service provides IIIF image api
        return $id;
    }
}

