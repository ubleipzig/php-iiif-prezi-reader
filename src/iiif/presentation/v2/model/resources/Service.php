<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\vocabulary\Names;

class Service
{
    /**
     * 
     * @var string
     */
    protected $id;
    /**
     * 
     * @var string
     */
    protected $context;
    /**
     * 
     * @var string
     */
    protected $profile;
    /**
     * @return mixed
     */
    
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
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getProfile()
    {
        return $this->profile;
    }
}

