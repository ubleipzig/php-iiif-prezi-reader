<?php
namespace iiif\model\resources;

use iiif\model\vocabulary\Names;

class Service
{
    protected $id;
    protected $context;
    protected $profile;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    public static function fromArray($array)
    {
        if ($array==null) return null;
        if (is_array($array))
        {
            $service = new Service(); 
            $service->id = array_key_exists(Names::ID, $array) ? $array[Names::ID] : null;
            $service->context = array_key_exists(Names::CONTEXT, $array) ? $array[Names::CONTEXT] : null;
            $service->profile = array_key_exists(Names::PROFILE, $array) ? $array[Names::PROFILE] : null;
            return $service;
        }
        elseif (is_string($array))
        {
//             $service = new Service();
//             $service->id = $array;
        }
        return null;
    }
    
}

