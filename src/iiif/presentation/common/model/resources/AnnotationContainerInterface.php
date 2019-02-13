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

namespace iiif\presentation\common\model\resources;


interface AnnotationContainerInterface extends IiifResourceInterface {
    
    /**
     * @param $motivation string The name of an instance of https://www.w3.org/ns/oa#Motivation,
     * usually "painting", respectively "sc:painting", or "oa:commenting".
     * @return AnnotationInterface[] All text annotations with the given $motivation,
     * or all text annotations if $motivation is null.
     * @link https://www.w3.org/TR/annotation-vocab/#motivation
     * 
     */
    public function getTextAnnotations($motivation = null);
    
}

