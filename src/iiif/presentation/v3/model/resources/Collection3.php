<?php
namespace iiif\presentation\v3\model\resources;

class Collection3 extends AbstractIiifResource3
{
    /**
     * 
     * @var (Collection3|Manifest3)[]
     */
    protected $items;
    
    /**
     * 
     * @var AnnotationPage3[]
     */
    protected $annotations;
    
    /**
     * 
     * @var Canvas3
     */
    protected $posterCanvas;
    
    /**
     * 
     * @var string
     */
    protected $navDate;
    
    /**
     * 
     * @var string
     */
    protected $viewingDirection;

    /**
     * @return multitype:Ambigous <\iiif\presentation\v3\model\resources\Collection3, \iiif\presentation\v3\model\resources\Manifest3>
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return multitype:\iiif\presentation\v3\model\resources\AnnotationPage3 
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * @return \iiif\presentation\v3\model\resources\Canvas3
     */
    public function getPosterCanvas()
    {
        return $this->posterCanvas;
    }

    /**
     * @return string
     */
    public function getNavDate()
    {
        return $this->navDate;
    }

    /**
     * @return string
     */
    public function getViewingDirection()
    {
        return $this->viewingDirection;
    }



}