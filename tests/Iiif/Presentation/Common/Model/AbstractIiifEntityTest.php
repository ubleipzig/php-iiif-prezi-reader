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
use Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface;
use Ubl\Iiif\Presentation\V2\Model\Resources\Manifest2;
use Ubl\Iiif\Tools\IiifHelper;

/**
 * AbstractIiifEntity test case.
 */
class AbstractIiifEntityTest extends AbstractIiifTest {

    /**
     * Tests \Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity->registerResource()
     */
    public function testRegisterResources() {
        
        $manifest = IiifHelper::loadIiifResource(self::getFile("v2/definition-order.json"));
        
        self::assertInstanceOf(Manifest2::class, $manifest);
        self::assertEquals(6, count($manifest->getDefaultCanvases()));
        self::assertEquals(5, count($manifest->getStructures()));
        self::assertEquals(1, count($manifest->getRootRanges()));
        self::assertEquals("http://example.org/iiif/id1/range/range0", $manifest->getRootRanges()[0]->getId());
        
        self::markTestIncomplete("implement");
    }

}

