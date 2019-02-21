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

namespace Ubl\Iiif\Presentation\V3\Model\Resources;

use Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity;

class SpecificResource3 extends AbstractIiifEntity {

    /**
     *
     * @var Canvas3
     */
    protected $source;

    /**
     *
     * @var mixed
     */
    protected $selector;

    /**
     *
     * {@inheritdoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity::getStringResources()
     */
    protected function getStringResources() {
        return [
            "source" => Canvas3::class
        ];
    }

    /**
     *
     * @return \Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3
     */
    public function getSource() {
        return $this->source;
    }

    /**
     *
     * @return mixed
     */
    public function getSelector() {
        return $this->selector;
    }
}

