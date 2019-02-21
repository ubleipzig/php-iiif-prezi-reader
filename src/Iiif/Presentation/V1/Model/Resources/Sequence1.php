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


class Sequence1 extends AbstractDescribableResource1 {
 
    /**
     * 
     * @var Canvas1[]
     */
    protected $canvases;
    
    /**
     * 
     * @var string
     */
    protected $viewingDirection;
    
    /**
     *
     * @var string
     */
    protected $viewingHint;
    /**
     * @return multitype:\Ubl\Iiif\Presentation\V1\Model\Resources\Canvas1 
     */
    public function getCanvases() {
        return $this->canvases;
    }

    /**
     * @return string
     */
    public function getViewingDirection() {
        return $this->viewingDirection;
    }

    /**
     * @return string
     */
    public function getViewingHint() {
        return $this->viewingHint;
    }
    
}

