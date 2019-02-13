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

namespace iiif\presentation\v3\model\resources;

use iiif\presentation\common\model\resources\AnnotationInterface;

class Annotation3 extends AbstractIiifResource3 implements AnnotationInterface {

    /**
     *
     * @var string
     */
    protected $timeMode;

    /**
     *
     * @var string
     */
    protected $motivation;

    /**
     *
     * @var (Canvas3|SpecificResource3)
     */
    protected $target;

    /**
     *
     * @var ContentResource3
     */
    protected $body;

    /**
     *
     * {@inheritdoc}
     * @see \iiif\presentation\common\model\AbstractIiifEntity::getStringResources()
     */
    protected function getStringResources() {
        return [
            "target" => Canvas3::class
        ];
    }

    /**
     *
     * @return string
     */
    public function getTimeMode() {
        return $this->timeMode;
    }

    /**
     *
     * @return string
     */
    public function getMotivation() {
        return $this->motivation;
    }

    /**
     *
     * @return (\iiif\presentation\v3\model\resources\Canvas3|\iiif\presentation\v3\model\resources\SpecificResource3)
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\ContentResource3
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v3\model\resources\AbstractIiifResource3::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result = parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        if ($this->motivation == "painting" && $this->getBody()!=null && $this->getBody()->getType() == "Image") {
            return $this->getBody()->getThumbnailUrl();
        }
        return null;
    }
    
}

