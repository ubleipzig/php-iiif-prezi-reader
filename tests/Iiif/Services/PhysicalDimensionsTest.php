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
use Ubl\Iiif\Tools\IiifHelper;
use Ubl\Iiif\Services\PhysicalDimensions;

/**
 * PhysicalDimensions test case.
 */
class PhysicalDimensionsTest extends AbstractIiifTest {

    /**
     *
     * @var PhysicalDimensions
     */
    private $physicalDimensions;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        $this->physicalDimensions = IiifHelper::loadIiifResource('{'.
           '"@context": "http://iiif.io/api/annex/services/physdim/1/context.json",'.
           '"profile": "http://iiif.io/api/annex/services/physdim",'.
           '"physicalScale": 0.0025,'.
           '"physicalUnits": "in"'.
          '}');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
    }

    /**
     * Tests PhysicalDimensions->getPhysicalScale()
     */
    public function testGetPhysicalScale() {
        self::assertInstanceOf(PhysicalDimensions::class, $this->physicalDimensions);
        self::assertEquals(0.0025, $this->physicalDimensions->getPhysicalScale());
    }

    /**
     * Tests PhysicalDimensions->getPhysicalUnits()
     */
    public function testGetPhysicalUnits() {
        $this->physicalDimensions->getPhysicalUnits(/* parameters */);
        self::assertInstanceOf(PhysicalDimensions::class, $this->physicalDimensions);
        self::assertEquals("in", $this->physicalDimensions->getPhysicalUnits());
    }
}

