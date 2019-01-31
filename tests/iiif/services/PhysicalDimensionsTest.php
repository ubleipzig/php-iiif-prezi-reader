<?php
use iiif\AbstractIiifTest;
use iiif\tools\IiifHelper;
use iiif\services\PhysicalDimensions;

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

