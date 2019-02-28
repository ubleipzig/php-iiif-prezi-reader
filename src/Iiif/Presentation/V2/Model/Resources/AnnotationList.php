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

namespace Ubl\Iiif\Presentation\V2\Model\Resources;

use Ubl\Iiif\Tools\IiifHelper;
use Ubl\Iiif\Presentation\Common\Model\Resources\AnnotationContainerInterface;
use Ubl\Iiif\Presentation\Common\Vocabulary\Motivation;

class AnnotationList extends AbstractIiifResource2 implements AnnotationContainerInterface {

    const TYPE = "sc:AnnotationList";

    /**
     *
     * @var Annotation[]
     */
    protected $resources = array();

    private $resourcesLoaded = false;


    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V2\Model\Resources\AbstractIiifResource2::getPropertyMap()
     */
    protected function getPropertyMap() {
        return array_merge(parent::getPropertyMap(), [
            "http://iiif.io/api/presentation/2#hasAnnotations" => "resources"
        ]);
    }

    /**
     *
     * @return \Ubl\Iiif\Presentation\V2\Model\Resources\Annotation[]
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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\AnnotationContainerInterface::getTextAnnotations()
     */
    public function getTextAnnotations($motivation = null) {
        $resources = $this->getResources();
        $textAnnotations = [];
        foreach ($resources as $annotation) {
            if (($motivation == null || array_search($annotation->getMotivation(), $motivation) !== false) && $annotation->getResource()!=null 
                && $annotation->getResource() instanceof ContentResource && $annotation->getResource()->getType()=="cnt:ContentAsText") {
                $textAnnotations[] = $annotation;
            }
        }
        return $textAnnotations;
    }
    
}

