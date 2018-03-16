<?php
namespace iiif\model\properties;

trait NavDateTrait
{
    protected $navDate;
    
    public function getNavDate()
    {
        return $this->navDate;
    }
    public function getNavDateAsDateTime()
    {
        return $this->navDate==null ? null : new \DateTime($this->navDate);
    }
}

