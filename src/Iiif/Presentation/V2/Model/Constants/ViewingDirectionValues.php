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

namespace Ubl\Iiif\Presentation\V2\Model\Constants;

class ViewingDirectionValues {

    const LEFT_TO_RIGHT = "left-to-right";

    const RIGHT_TO_LEFT = "right-to-left";

    const TOP_TO_BOTTOM = "top-to-bottom";

    const BOTTOM_TO_TOP = "bottom-to-top";

    const ALLOWED_VALUES = [
        self::LEFT_TO_RIGHT,
        self::RIGHT_TO_LEFT,
        self::TOP_TO_BOTTOM,
        self::BOTTOM_TO_TOP
    ];
}

