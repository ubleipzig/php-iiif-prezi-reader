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
    
    public static function fromArray($jsonAsArray)
    {
        $manifest = new Manifest();
        $manifest->loadPropertiesFromArray($jsonAsArray);
        $manifest->loadResources($jsonAsArray, Names::SEQUENCES, Sequence::class, $manifest->sequences);
        $manifest->loadResources($jsonAsArray, Names::STRUCTURES, Range::class, $manifest->structures);
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
}


