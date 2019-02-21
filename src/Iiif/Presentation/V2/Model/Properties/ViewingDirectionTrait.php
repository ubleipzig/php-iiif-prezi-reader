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

namespace Ubl\Iiif\Presentation\V2\Model\Properties;

use Exception;
use Ubl\Iiif\Presentation\V2\Model\Constants\ViewingDirectionValues;

trait ViewingDirectionTrait
{

    protected $viewingDirection;

    /**
     *
     * @return string
     */
    public function getViewingDirection() {
        return $this->viewingDirection;
    }

    /**
     *
     * @param string $viewingDirection
     */
    public function setViewingDirection($viewingDirection) {
        if (! is_null($viewingDirection) && ! is_string($viewingDirection))
            throw new Exception("Wrong type for viewingDirection");
        if ($viewingDirection != null && $viewingDirection != '' && ! in_array($viewingDirection, ViewingDirectionValues::ALLOWED_VALUES))
            throw new Exception("Unknown viewingDirection " . $viewingDirection);
        $this->viewingDirection = $viewingDirection == '' ? null : $viewingDirection;
    }
}

