<?php
namespace iiif\model\resources;

use iiif\model\properties\NavDateTrait;
use iiif\model\properties\ViewingDirectionTrait;
use iiif\model\vocabulary\Names;

class Manifest extends AbstractIiifResource
{
    use NavDateTrait;
    use ViewingDirectionTrait;
    
    const CONTEXT="http://iiif.io/api/presentation/2/context.json";
    const TYPE="sc:Manifest";
    
    /**
     * 
     * @var Sequence[]
     */
    protected $sequences = array();
    
    protected $viewingDirection;
    protected $navDate;
    
    /**
     * 
     * @var Range[]
     */
    protected $structures = array();
    
    /**
     * 
     * @var AbstractIiifResource[]
     */
    protected $containedResources = array();
    
    public static function fromArray($jsonAsArray, &$allResources = array())
    {
        $manifest = self::createInstanceFromArray($jsonAsArray, $allResources);
        $manifest->loadPropertiesFromArray($jsonAsArray, $allResources);
        /* @var $manifest Manifest */
        $manifest->containedResources=&$allResources;
        $manifest->loadResources($jsonAsArray, Names::SEQUENCES, Sequence::class, $manifest->sequences, $manifest->containedResources);
        $manifest->loadResources($jsonAsArray, Names::STRUCTURES, Range::class, $manifest->structures, $manifest->containedResources);
        $manifest->navDate = array_key_exists(Names::NAV_DATE, $jsonAsArray) ? $jsonAsArray[Names::NAV_DATE] : null;
        return $manifest;
    }
    /**
     * @return Sequence[]:
     */
    public function getSequences()
    {
        return $this->sequences;
    }
    /**
     * @return multitype:\iiif\model\resources\Range 
     */
    public function getStructures()
    {
        return $this->structures;
    }
    
    public function getContainedResourceById($id)
    {
        if (array_key_exists($id, $this->containedResources)) return $this->containedResources[$id];
    }
}


