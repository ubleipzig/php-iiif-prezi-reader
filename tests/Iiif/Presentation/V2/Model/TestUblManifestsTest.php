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

use Ubl\Iiif\AbstractIiifTest;
use Ubl\Iiif\Presentation\V2\Model\Resources\Manifest2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Sequence2;
use Ubl\Iiif\Tools\IiifHelper;

class TestUblManifestsTest extends AbstractIiifTest
{
    const MANIFEST_EXAMPLES = array('v2/manifest-0000006761.json', 'v2/manifest-0000000720.json');
    
    public function testUBLManifest()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $manifestAsJson=self::getFile($manifestFile);
            Manifest2::loadIiifResource($manifestAsJson, $manifestFile." did not load");
        }
    }
    
    public function testUBLIiifHelperIiifResourceFromJsonString()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $documentAsJson=self::getFile($manifestFile);
            $resource=IiifHelper::loadIiifResource($documentAsJson);
            self::assertInstanceOf(Manifest2::class, $resource, 'Not a manifest: '.$manifestFile);
            self::assertNotNull($resource->getSequences(), 'No sequences found: '.$manifestFile);
            self::assertNotEmpty($resource->getSequences(), 'Sequences empty: '.$manifestFile);
            self::assertNotNull($resource->getSequences()[0], 'First sequence not found: '.$manifestFile);
            self::assertInstanceOf(Sequence2::class, $resource->getSequences()[0], 'First sequence is not a sequence: '.$manifestFile);
        }
    }
}

