<?php
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

