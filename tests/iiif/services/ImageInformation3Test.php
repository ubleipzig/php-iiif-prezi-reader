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

use iiif\AbstractIiifTest;
use iiif\context\JsonLdProcessor;
use iiif\context\JsonLdContext;

/**
 * ImageInformation3 test case.
 */
class ImageInformation3Test extends AbstractIiifTest {

    /**
     * Tests ImageInformation3->getFormats()
     */
    public function testGetFormats() {
        $this->markTestIncomplete("getFormats test not implemented");
    }
    
    /**
     * Tests ImageInformation3->getQualities()
     */
    public function testGetQualities() {
        $this->markTestIncomplete("getQualities test not implemented");
    }
    
    /**
     * Tests ImageInformation3->getSupports()
     */
    public function testGetSupports() {
        
        $this->markTestIncomplete("getSupports test not implemented");
    }
    
    /**
     * Tests ImageInformation3->isFeatureSupported()
     */
    public function testIsFeatureSupported() {

        $this->markTestIncomplete("isFeatureSupported test not implemented");
    }
    
    /**
     * Tests ImageInformation3->getImageUrl()
     */
    public function testGetImageUrl() {
        // TODO
        $this->markTestIncomplete("getImageUrl test not implemented");
    }
    
    /**
     * @see https://github.com/IIIF/api/pull/1774
     */
    public function testUndefinedTypes() {
        $processor = new JsonLdProcessor();
        $context = $processor->processContext("http://iiif.io/api/image/3/context.json", new JsonLdContext($processor));
        $tileDefinition = $context->getTermDefinition("Tile");
        self::assertNotNull($tileDefinition);
        self::assertEquals("http://iiif.io/api/image/3#Tile", $tileDefinition->getIriMapping());
        $sizeDefinition = $context->getTermDefinition("Size");
        self::assertNotNull($sizeDefinition);
        self::assertEquals("http://iiif.io/api/image/3#Size", $sizeDefinition->getIriMapping());
    }
}

