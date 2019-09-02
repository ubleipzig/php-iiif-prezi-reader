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

namespace Ubl\Iiif\Presentation\Common\Model\Resources;

use Ubl\Iiif\Presentation\Common\Model\LazyLoadingIterator;

interface CanvasInterface extends IiifResourceInterface {
    
    /**
     * @return AnnotationInterface[] All embedded image annotations.
     */
    public function getImageAnnotations();
    
    /**
     * @return AnnotationContainerInterface[]
     */
    public function getPossibleTextAnnotationContainers($motivation = null);
    
    /**
     * 
     * @param boolean $painting 
     * @return LazyLoadingIterator
     */
    public function getPotentialTextAnnotationContainerIterator($painting = null);
    
    /**
     * @return int
     */
    public function getWidth();
    
    /**
     * @return int
     */
    public function getHeight();
}

