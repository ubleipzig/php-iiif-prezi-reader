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

namespace iiif\presentation\v1\model\resources;


use iiif\presentation\common\model\resources\AnnotationInterface;

class Annotation1 extends AbstractIiifResource1 implements AnnotationInterface {

    /**
     * 
     * @var string
     */
    protected $motivation;

    /**
     * 
     * @var ContentResource1
     */
    protected $resource;

    /**
     * 
     * @var mixed
     */
    protected $on;
    /**
     * @return string
     */
    public function getMotivation() {
        return $this->motivation;
    }

    /**
     * @return \iiif\presentation\v1\model\resources\ContentResource1
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     * @return mixed
     */
    public function getOn() {
        return $this->on;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v1\model\resources\AbstractIiifResource1::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        if ($this->motivation == "sc:painting" && $this->resource!=null && $this->resource instanceof ContentResource1) {
            return $this->resource->getThumbnailUrl();
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\AnnotationInterface::getBody()
     */
    public function getBody() {
        return $this->resource;
    }
    
}

