<?php
namespace iiif\model\properties;

use Exception;
use iiif\model\constants\ViewingDirectionValues;

trait ViewingDirectionTrait
{
    protected $viewingDirection;
    
    /**
     * @return string
     */
    public function getViewingDirection()
    {
        return $this->viewingDirection;
    }

    /**
     * @param string $viewingDirection
     */
    public function setViewingDirection($viewingDirection)
    {
        if (!is_null($viewingDirection) || !is_string($viewingDirection)) throw new Exception("Wrong type for viewingDirection");
        if ($viewingDirection!='' && !in_array($viewingDirection, ViewingDirectionValues::ALLOWED_VALUES)) throw new Exception("Unknown viewingDirection " . $viewingDirection);
        $this->viewingDirection = $viewingDirection == '' ? null : $viewingDirection;
    }
}

