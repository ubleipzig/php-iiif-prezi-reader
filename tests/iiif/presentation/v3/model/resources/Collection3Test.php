<?php

use iiif\AbstractIiifTest;
use iiif\presentation\v3\model\resources\Collection3;
use iiif\tools\IiifHelper;
use iiif\presentation\v3\model\resources\Manifest3;

/**
 * Collection3 test case.
 */
class Collection3Test extends AbstractIiifTest {

    /**
     * @var Collection3
     */
    private $collection1;
    /**
     * @var Collection3
     */
    private $collection2;
    /**
     * @var Collection3
     */
    private $collection3;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        $this->collection1 = IiifHelper::loadIiifResource(self::getFile("v3/collection1.json"));
        $this->collection2 = IiifHelper::loadIiifResource(self::getFile("v3/collection2.json"));
        $this->collection3 = IiifHelper::loadIiifResource(self::getFile("v3/collection3.json"));
    }

    public function testGetContainedCollections() {
        self::assertNotNull($this->collection1);
        self::assertInstanceOf(Collection3::class, $this->collection1);
        self::assertEquals("http://example.org/collection/collection1", $this->collection1->getId());
        self::assertEmpty($this->collection1->getContainedCollections());

        self::assertNotNull($this->collection2);
        self::assertInstanceOf(Collection3::class, $this->collection2);
        self::assertEquals("http://example.org/collection/collection2", $this->collection2->getId());
        self::assertEmpty($this->collection2->getContainedCollections());
        
        self::assertNotNull($this->collection3);
        self::assertInstanceOf(Collection3::class, $this->collection3);
        self::assertEquals("http://example.org/collection/collection3", $this->collection3->getId());
        self::assertNotEmpty($this->collection3->getContainedCollections());
        self::assertEquals(2, sizeof($this->collection3->getContainedCollections()));

        foreach ($this->collection3->getContainedCollections() as $item) {
            switch ($item->getId()) {
                case "http://example.org/collection/subcollection1":
                case "http://example.org/collection/subcollection2":
                    self::assertInstanceOf(Collection3::class, $item);
                    break;
                default:
                    self::fail("Unexpected id in contained collections ".$item->getId());
            }
        }
    }

    public function testGetContainedCollectionsAndManifests() {
        self::assertNotNull($this->collection1);
        self::assertInstanceOf(Collection3::class, $this->collection1);
        self::assertEquals("http://example.org/collection/collection1", $this->collection1->getId());
        self::assertEmpty($this->collection1->getContainedCollectionsAndManifests());
        
        self::assertNotNull($this->collection2);
        self::assertInstanceOf(Collection3::class, $this->collection2);
        self::assertEquals("http://example.org/collection/collection2", $this->collection2->getId());
        self::assertEmpty($this->collection2->getContainedCollectionsAndManifests());
        
        self::assertNotNull($this->collection3);
        self::assertInstanceOf(Collection3::class, $this->collection3);
        self::assertEquals("http://example.org/collection/collection3", $this->collection3->getId());
        self::assertNotEmpty($this->collection3->getContainedCollections());
        self::assertEquals(4, sizeof($this->collection3->getContainedCollectionsAndManifests()));
        
        foreach ($this->collection3->getContainedCollectionsAndManifests() as $item) {
            switch ($item->getId()) {
                case "http://example.org/collection/subcollection1":
                case "http://example.org/collection/subcollection2":
                    self::assertInstanceOf(Collection3::class, $item);
                    break;
                case "http://example.org/manifest/manifest1":
                case "http://example.org/manifest/manifest2":
                    self::assertInstanceOf(Manifest3::class, $item);
                    break;
                default:
                    self::fail("Unexpected id in contained collections and manifests ".$item->getId());
            }
        }
    }

    public function testGetContainedManifests() {
        self::assertNotNull($this->collection1);
        self::assertInstanceOf(Collection3::class, $this->collection1);
        self::assertEquals("http://example.org/collection/collection1", $this->collection1->getId());
        self::assertEmpty($this->collection1->getContainedManifests());
        
        self::assertNotNull($this->collection2);
        self::assertInstanceOf(Collection3::class, $this->collection2);
        self::assertEquals("http://example.org/collection/collection2", $this->collection2->getId());
        self::assertEmpty($this->collection2->getContainedManifests());
        
        self::assertNotNull($this->collection3);
        self::assertInstanceOf(Collection3::class, $this->collection3);
        self::assertEquals("http://example.org/collection/collection3", $this->collection3->getId());
        self::assertNotEmpty($this->collection3->getContainedCollections());
        self::assertEquals(2, sizeof($this->collection3->getContainedManifests()));
        
        foreach ($this->collection3->getContainedManifests() as $item) {
            switch ($item->getId()) {
                case "http://example.org/manifest/manifest1":
                case "http://example.org/manifest/manifest2":
                    self::assertInstanceOf(Manifest3::class, $item);
                    break;
                default:
                    self::fail("Unexpected id in contained manifests ".$item->getId());
            }
        }
    }
}

