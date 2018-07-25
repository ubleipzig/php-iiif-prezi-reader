<?php
namespace iiif\presentation\v3\model\resources;

abstract class AbstractIiifResource3
{
    protected $id;
    protected $type;
    protected $behaviour;
    
    protected $label;
    protected $metadata;
    protected $summary;
    protected $thumbnail;
    protected $requiredStatement;
    protected $rights;
    
    protected $seeAlso;
    protected $service;
    protected $logo;
    protected $homepage;
    protected $rendering;
    protected $partOf;
    
    protected $originalJsonArray;

    protected function loadProperty($term) {
        // TODO 
        $this->$term = "test";
    }
    
    // posterCanvas
    // navDate
    // language
    // format
    // profile
    // height
    // width
    // duration
    // viewingDirection
    // timeMode
    // start
    // supplementary
    
}

