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

namespace Ubl\Iiif\Presentation\V1\Model\Resources;


use Ubl\Iiif\Presentation\Common\Model\Resources\AnnotationContainerInterface;

class AnnotationList1 extends AbstractIiifResource1 implements AnnotationContainerInterface {
    
    /**
     * 
     * @var Annotation1[]
     */
    protected $resources;
    
    /**
     * @return multitype:\Ubl\Iiif\Presentation\V1\Model\Resources\Annotation1 
     */
    public function getResources() {
        // TODO if the annotation list is only linked in the document, get the remote content
        return $this->resources;
    }
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\AnnotationContainerInterface::getTextAnnotations()
     */
    public function getTextAnnotations($motivation = null) {
        // TODO Auto-generated method stub
        
    }
    
}

