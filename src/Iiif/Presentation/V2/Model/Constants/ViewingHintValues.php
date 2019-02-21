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

class ViewingHintValues {

    const INDIVIDUALS = "individuals";

    const PAGED = "paged";

    const CONTINUOUS = "continuous";

    const MULTI_PART = "multi-part";

    const NON_PAGED = "non-paged";

    const TOP = "top";

    const FACING_PAGES = "facing-pages";

    const ALLOWED_VALUES = [
        ViewingHintValues::INDIVIDUALS,
        ViewingHintValues::PAGED,
        ViewingHintValues::CONTINUOUS,
        ViewingHintValues::MULTI_PART,
        ViewingHintValues::NON_PAGED,
        ViewingHintValues::TOP,
        ViewingHintValues::FACING_PAGES
    ];
}

