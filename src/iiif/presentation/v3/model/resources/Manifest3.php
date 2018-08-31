<?php
namespace iiif\presentation\v3\model\resources;

class Manifest3 extends AbstractIiifResource3
{
    protected $items;
    protected $structures;
    protected $annotations;
    protected $posterCanvas;
    protected $navDate;
    protected $viewingDirection;
    
    /**
     * 
     * @var Canvas3
     */
    protected $start;

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return mixed
     */
    public function getStructures()
    {
        return $this->structures;
    }

    /**
     * @return mixed
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * @return mixed
     */
    public function getPosterCanvas()
    {
        return $this->posterCanvas;
    }

    /**
     * @return mixed
     */
    public function getNavDate()
    {
        return $this->navDate;
    }

    /**
     * @return mixed
     */
    public function getViewingDirection()
    {
        return $this->viewingDirection;
    }

    /**
     * @return Canvas3
     */
    public function getStart()
    {
        return $this->start;
    }


}

