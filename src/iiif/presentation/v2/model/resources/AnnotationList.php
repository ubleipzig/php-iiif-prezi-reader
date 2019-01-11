<?php
namespace iiif\presentation\v2\model\resources;

use iiif\tools\IiifHelper;

class AnnotationList extends AbstractIiifResource {

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

}

