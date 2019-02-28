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
use Ubl\Iiif\Presentation\V2\Model\Resources\ContentResource2;

/**
 * ContentResource test case.
 */
class ContentResource2Test extends AbstractIiifTest {

    /**
     *
     * @var ContentResource2
     */
    private $contentResource;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp();
        
        // TODO Auto-generated ContentResource2Test::setUp()
        
        $this->contentResource = new ContentResource2(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        // TODO Auto-generated ContentResource2Test::tearDown()
        $this->contentResource = null;
        
        parent::tearDown();
    }

    /**
     * Tests ContentResource2->getFormat()
     */
    public function testGetFormat() {
        // TODO Auto-generated ContentResource2Test->testGetFormat()
        $this->markTestIncomplete("getFormat test not implemented");
        
        $this->contentResource->getFormat(/* parameters */);
    }

    /**
     * Tests ContentResource2->getChars()
     */
    public function testGetChars() {
        // TODO Auto-generated ContentResource2Test->testGetChars()
        $this->markTestIncomplete("getChars test not implemented");
        
        $this->contentResource->getChars(/* parameters */);
    }

    /**
     * Tests ContentResource2->getImageUrl()
     */
    public function testGetImageUrl() {
        // TODO Auto-generated ContentResource2Test->testGetImageUrl()
        $this->markTestIncomplete("getImageUrl test not implemented");
        
        $this->contentResource->getImageUrl(/* parameters */);
    }
}

