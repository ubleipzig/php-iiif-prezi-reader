<?php
namespace iiif\presentation\v2\model\resources;

use iiif\tools\IiifHelper;
use iiif\presentation\common\model\resources\AnnotationContainerInterface;

class AnnotationList extends AbstractIiifResource2 implements AnnotationContainerInterface {

    const TYPE = "sc:AnnotationList";

    /**
     *
     * @var Annotation[]
     */
    protected $resources = array();

    private $resourcesLoaded = false;

    /**
     *
     * @return \iiif\presentation\v2\model\resources\Annotation[]
     */
    public function getResources() {
        if ($resources == null && ! $this->resourcesLoaded) {
            
            $content = IiifHelper::getRemoteContent($this->id);
            $jsonAsArray = json_decode($content, true);
            
            $remoteAnnotationList = IiifHelper::loadIiifResource($content);
            
            $this->resources = $remoteAnnotationList->resources;
            
            // TODO register resources in manifest (i.e. replace $dummy with actual resources array somehow)
            
            $this->resourcesLoaded = true;
        }
        return $this->resources;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\AnnotationContainerInterface::getTextAnnotations()
     */
    public function getTextAnnotations($motivation = null) {
        $resources = $this->getResources();
        $textAnnotations = [];
        foreach ($resources as $annotation) {
            if ($annotation->getMotivation() == "sc:painting" && $annotation->getResource()!=null 
                && $annotation->getResource() instanceof ContentResource && $annotation->getResource()->getType()=="cnt:ContentAsText") {
                $textAnnotations[] = $annotation;
            }
        }
        return $textAnnotations;
    }
    
}

