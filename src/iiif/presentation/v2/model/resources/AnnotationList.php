<?php
/*
 * Copyright (C) 2019 Leipzig University Library <info@ub.uni-leipzig.de>
 * 
 * This file is part of the php-iiif-prezi-reader.
 * 
 * php-iiif-prezi-reader is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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

