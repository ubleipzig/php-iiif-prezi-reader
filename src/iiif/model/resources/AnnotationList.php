<?php
namespace iiif\model\resources;

use iiif\model\vocabulary\Names;

class AnnotationList extends AbstractIiifResource
{
    const TYPE="sc:AnnotationList";
    
    /**
     * 
     * @var Annotation[]
     */
    protected $resources = array();
    
    private $resourcesLoaded = false;
    
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources=array())
    {
        $annotationList = self::createInstanceFromArray($jsonAsArray, $allResources);
        $annotationList->loadPropertiesFromArray($jsonAsArray, $allResources);
        $annotationList->loadResources($jsonAsArray, Names::RESOURCES, Annotation::class, $annotationList->resources, $allResources);
        return $annotationList;
    }

    /**
     * @return \iiif\model\resources\Annotation[] 
     */
    public function getResources()
    {
        if ($resources == null && !$this->resourcesLoaded) {
            
            $content = file_get_contents($this->id);
            $json = json_decode($content, true);
            
            $annotationList->loadPropertiesFromArray($jsonAsArray, array());
            $annotationList->loadResources($jsonAsArray, Names::RESOURCES, Annotation::class, $annotationList->resources, array());
            
            // TODO register resources in manifest
            
            $this->resourcesLoaded = true;
        }
        return $this->resources;
    }
}

