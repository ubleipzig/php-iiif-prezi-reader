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

namespace Ubl\Iiif\Context;

/**
 * JSON-LD Keywords
 * 
 * @lnk https://www.w3.org/TR/json-ld11/#syntax-tokens-and-keywords
 * @author lutzhelm
 *
 */
class Keywords {

    const BASE = "@base";

    const CONTAINER = "@container";

    const CONTEXT = "@context";

    const GRAPH = "@graph";

    const ID = "@id";

    const INDEX = "@index";

    const LANGUAGE = "@language";

    const LIST_ = "@list";

    const NEST = "@nest";

    const NONE = "@none";

    const PREFIX = "@prefix";
    
    const PROTECTED_ = "@protected";

    const REVERSE = "@reverse";

    const SET = "@set";

    const TYPE = "@type";

    const VALUE = "@value";

    const VERSION = "@version";

    const VOCAB = "@vocab";

    const KEYWORDS = array(
        self::BASE,
        self::CONTAINER,
        self::CONTEXT,
        self::GRAPH,
        self::ID,
        self::INDEX,
        self::LANGUAGE,
        self::LIST_,
        self::NEST,
        self::NONE,
        self::PREFIX,
        self::PROTECTED_,
        self::REVERSE,
        self::SET,
        self::TYPE,
        self::VALUE,
        self::VERSION,
        self::VOCAB
    );

    /**
     * 
     * @param string $term
     * @return boolean true if the given $term is a JSON-LD keyword, otherwise false
     * @link https://www.w3.org/TR/json-ld11/#syntax-tokens-and-keywords
     */
    public static function isKeyword($term) {
        return array_search($term, self::KEYWORDS) !== false;
    }
}

