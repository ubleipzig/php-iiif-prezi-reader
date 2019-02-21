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
use Ubl\Iiif\Presentation\V2\Model\Resources\ContentResource;

/**
 * ContentResource test case.
 */
class ContentResourceTest extends AbstractIiifTest {

    /**
     *
     * @var ContentResource
     */
    private $contentResource;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp();
        
        // TODO Auto-generated ContentResourceTest::setUp()
        
        $this->contentResource = new ContentResource(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        // TODO Auto-generated ContentResourceTest::tearDown()
        $this->contentResource = null;
        
        parent::tearDown();
    }

    /**
     * Tests ContentResource->getFormat()
     */
    public function testGetFormat() {
        // TODO Auto-generated ContentResourceTest->testGetFormat()
        $this->markTestIncomplete("getFormat test not implemented");
        
        $this->contentResource->getFormat(/* parameters */);
    }

    /**
     * Tests ContentResource->getChars()
     */
    public function testGetChars() {
        // TODO Auto-generated ContentResourceTest->testGetChars()
        $this->markTestIncomplete("getChars test not implemented");
        
        $this->contentResource->getChars(/* parameters */);
    }

    /**
     * Tests ContentResource->getImageUrl()
     */
    public function testGetImageUrl() {
        // TODO Auto-generated ContentResourceTest->testGetImageUrl()
        $this->markTestIncomplete("getImageUrl test not implemented");
        
        $this->contentResource->getImageUrl(/* parameters */);
    }
}

