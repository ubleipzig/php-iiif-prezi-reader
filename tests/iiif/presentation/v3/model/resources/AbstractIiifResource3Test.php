<?php

use iiif\presentation\v3\model\resources\AbstractIiifResource3;

/**
 *  test case.
 */
class AbstractIiifResource3Test extends PHPUnit_Framework_TestCase
{

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated AbstractIiifResource3Test::setUp()
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated AbstractIiifResource3Test::tearDown()
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }
    
    
    
    public function testLoadIiifResource() {
        $resource = "file://".__DIR__."/../../../../../resources/v3/manifest3-example.json";
        AbstractIiifResource3::loadIiifResource($resource);
    }
}

;