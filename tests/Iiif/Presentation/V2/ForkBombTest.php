<?php

use PHPUnit\Framework\TestCase;
use Ubl\Iiif\Tools\IiifHelper;

/**
 *  test case.
 */
class ForkBombTest extends TestCase {

    public function testForkBomb() {
        $collection = IiifHelper::loadIiifResource("http://evil-manifests.davidnewbury.com/iiif/garden/fork.json");
        self::assertNotNull($collection);
    }
    
}

