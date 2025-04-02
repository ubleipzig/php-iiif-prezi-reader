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

use PHPUnit\Framework\TestCase;

/**
 *  Test case to ensure contexts are up to date
 */
class ContextResourceTest extends TestCase {
    
    /**
     * @var array Local conbtext
     */
    const CONTEXTS = [
        "http://www.w3.org/ns/anno.jsonld" => "annotation/annotation-context.json",
        "http://iiif.io/api/auth/1/context.json" => "iiif/auth-context-1.json",
        "http://iiif.io/api/image/1/context.json" => "iiif/image-context-1.json",
        "http://iiif.io/api/image/2/context.json" => "iiif/image-context-2.json",
        "http://iiif.io/api/image/3/context.json" => "iiif/image-context-3.json",
        "http://iiif.io/api/presentation/1/context.json" => "iiif/presentation-context-1.json",
        "http://iiif.io/api/presentation/2/context.json" => "iiif/presentation-context-2.json",
        "http://iiif.io/api/presentation/3/context.json" => "iiif/presentation-context-3.json",
        "http://iiif.io/api/search/1/context.json" => "iiif/search-context-1.json"
    ];

    /**
     * Ensure that the JSON-LD contexts that are provided with this library are the same as the online resources.
     */
    public function testLocalResourcesEqualRemoteResources() {
        foreach (self::CONTEXTS as $url => $localFilename) {
            $remoteContent = file_get_contents($url);
            $localContent = file_get_contents(__DIR__."/../../../resources/contexts/".$localFilename);
            self::assertEquals($remoteContent, $localContent, "Local version of ".$url." different from remote version.");
        }
    }
    
}

