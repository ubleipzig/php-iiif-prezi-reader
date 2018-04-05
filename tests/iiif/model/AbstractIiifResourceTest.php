<?php
use iiif\model\resources\MockIiifResource;
use iiif\model\vocabulary\Names;

/**
 * AbstractIiifResource test case.
 */
class AbstractIiifResourceTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var MockIiifResource
     */
    private $abstractIiifResource;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated AbstractIiifResourceTest::setUp()
        
        $this->abstractIiifResource = new MockIiifResource();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated AbstractIiifResourceTest::tearDown()
        $this->abstractIiifResource = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests AbstractIiifResource::fromJson()
     */
    public function testFromJson()
    {
        // TODO Auto-generated AbstractIiifResourceTest::testFromJson()
        $this->markTestIncomplete("fromJson test not implemented");
        
        MockIiifResource::fromJson(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->getDefaultLabel()
     */
    public function testGetDefaultLabel()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testGetDefaultLabel()
        $this->markTestIncomplete("getDefaultLabel test not implemented");
        
        $this->abstractIiifResource->getDefaultLabel(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->isReference()
     */
    public function testIsReference()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testIsReference()
        $this->markTestIncomplete("isReference test not implemented");
        
        $this->abstractIiifResource->isReference(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->getId()
     */
    public function testGetId()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testGetId()
        $this->markTestIncomplete("getId test not implemented");
        
        $this->abstractIiifResource->getId(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->getService()
     */
    public function testGetService()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testGetService()
        $this->markTestIncomplete("getService test not implemented");
        
        $this->abstractIiifResource->getService(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->getThumbnail()
     */
    public function testGetThumbnail()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testGetThumbnail()
        $this->markTestIncomplete("getThumbnail test not implemented");
        
        $this->abstractIiifResource->getThumbnail(/* parameters */);
    }
    
    /**
     * Tests AbstractIiifResource->getTranslatedLabel()
     */
    public function testGetTranslatedLabel()
    {
        // no label
        $this->abstractIiifResource->setLabel(null);
        $label = $this->abstractIiifResource->getTranslatedLabel();
        self::assertNull($label);
        $label = $this->abstractIiifResource->getTranslatedLabel("en");
        self::assertNull($label);
        
        // string as label
        $this->prepareLabel('My label');
        $label = $this->abstractIiifResource->getTranslatedLabel();
        self::assertEquals('My label', $label);
        $label = $this->abstractIiifResource->getTranslatedLabel('en');
        self::assertEquals('My label', $label);

        // multiple strings as label
        $this->prepareLabel('["My label", "My second label", "My third label"]');
        $label = $this->abstractIiifResource->getTranslatedLabel();
        self::assertTrue(is_array($label));
        self::assertContains('My label', $label);
        self::assertContains('My second label', $label);
        self::assertContains('My third label', $label);
        
        $label = $this->abstractIiifResource->getTranslatedLabel('en');
        self::assertTrue(is_array($label));
        self::assertContains('My label', $label);
        self::assertContains('My second label', $label);
        self::assertContains('My third label', $label);
        
        // translated label
        $this->prepareLabel('[{"@value": "Title", "@language": "en"}, {"@value": "Titel", "@language": "de"}, {"@value": "Intitulé", "@language": "fr"}]');
        $label = $this->abstractIiifResource->getTranslatedLabel();
        self::assertEquals('Title', $label);
        $label = $this->abstractIiifResource->getTranslatedLabel('en');
        self::assertEquals('Title', $label);
        $label = $this->abstractIiifResource->getTranslatedLabel('de');
        self::assertEquals('Titel', $label);
        $label = $this->abstractIiifResource->getTranslatedLabel('fr');
        self::assertEquals('Intitulé', $label);
        $label = $this->abstractIiifResource->getTranslatedLabel('ru');
        self::assertEquals('Title', $label);
    }

    /**
     * Tests AbstractIiifResource->getMetadateForLabel()
     */
    public function testGetMetadataForLabel()
    {
        // Test null value
        $this->abstractIiifResource->setMetadata(null);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Hopfully no error if metadata is set to null");
        self::assertNull($metadataValue);
        
        // Test empty metadata
        $this->prepareMetadata('[]');
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Hopfully no error for empty metadata");
        self::assertNull($metadataValue);
        
        // Test untranslated metadata
        $this->prepareMetadata('[{"label": "My label", "value": "My value"}]');
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label");
        self::assertEquals("My value", $metadataValue);

        // Test translated metadata
        $this->prepareMetadata('[{"label": [{"@value": "My label", "@language": "en"}, {"@value": "Meine Beschriftung", "@language": "de"}],'.
            ' "value": [{"@value": "My value", "@language": "en"}, {"@value": "Mein Wert", "@language": "de"}]}]');
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label");
        self::assertEquals("My value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label", "de");
        self::assertEquals("Mein Wert", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine Beschriftung");
        self::assertEquals("Mein Wert", $metadataValue);
        
        // Test translated metadata with multiple entries
        $this->prepareMetadata('[{"label": [{"@value": "My label", "@language": "en"}, {"@value": "Meine Beschriftung", "@language": "de"}],'.
            ' "value": [{"@value": "My value", "@language": "en"}, {"@value": "Mein Wert", "@language": "de"}]},'.
            ' {"label": [{"@value": "My other label", "@language": "en"}, {"@value": "Meine andere Beschriftung", "@language": "de"}],'.
            ' "value": [{"@value": "My other value", "@language": "en"}, {"@value": "Mein anderer Wert", "@language": "de"}]}]');
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label", "de");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label", "en");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label");
        self::assertEquals("My value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My other label");
        self::assertEquals("My other value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine Beschriftung");
        self::assertEquals("Mein Wert", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine andere Beschriftung");
        self::assertEquals("Mein anderer Wert", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My other label", "de");
        self::assertEquals("Mein anderer Wert", $metadataValue);
        
        // Test metadata with untranslated label and translated values
        $this->prepareMetadata('[{"label": "My label",'.
            ' "value": [{"@value": "My value", "@language": "en"}, {"@value": "Mein Wert", "@language": "de"}]},'.
            ' {"label": "Meine andere Beschriftung",'.
            ' "value": [{"@value": "My other value", "@language": "en"}, {"@value": "Mein anderer Wert", "@language": "de"}]}]');
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label");
        self::assertEquals("My value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label", "en");
        self::assertEquals("My value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label", "de");
        self::assertEquals("Mein Wert", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine andere Beschriftung");
        self::assertEquals("My other value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine andere Beschriftung", "en");
        self::assertEquals("My other value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine andere Beschriftung", "de");
        self::assertEquals("Mein anderer Wert", $metadataValue);
        
        
        // Test metadata with translated label and untranslated values
        $this->prepareMetadata('[{"label": [{"@value": "My label", "@language": "en"}, {"@value": "Meine Beschriftung", "@language": "de"}],'.
            ' "value": "My value"},'.
            ' {"label": [{"@value": "My other label", "@language": "en"}, {"@value": "Meine andere Beschriftung", "@language": "de"}],'.
            ' "value": "Mein anderer Wert"}]');
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label");
        self::assertEquals("My value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label", "en");
        self::assertEquals("My value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label", "de");
        self::assertEquals("My value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine Beschriftung");
        self::assertEquals("My value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine Beschriftung", "de");
        self::assertEquals("My value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My other label");
        self::assertEquals("Mein anderer Wert", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine andere Beschriftung");
        self::assertEquals("Mein anderer Wert", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Meine andere Beschriftung", "en");
        self::assertEquals("Mein anderer Wert", $metadataValue);
        
        // Test metadata with conflicting translations
        $this->prepareMetadata('[{"label": [{"@value": "Date", "@language": "en"}, {"@value": "Datum", "@language": "de"}],'.
            ' "value": [{"@value": "My date", "@language": "en"}, {"@value": "Mein Datum", "@language": "de"}]},'.
            ' {"label": [{"@value": "Datum", "@language": "en"}, {"@value": "Messwert", "@language": "de"}],'.
            ' "value": [{"@value": "My datum", "@language": "en"}, {"@value": "Mein Messwert", "@language": "de"}]}]');
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Date");
        self::assertEquals("My date", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Datum");
        self::assertEquals("Mein Datum", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Datum", "de");
        self::assertEquals("Mein Datum", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Datum", "en");
        self::assertEquals("My datum", $metadataValue);

        // Test metadata with arrays of values
        $this->prepareMetadata('[{"label": "My label", "value": ["My value", "My second value", "My third value"]}, '.
            '{"label": "My other label", "value": ["My other value", "My second other value", "My third other value"]}]');
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label");
        self::assertTrue(is_array($metadataValue));
        self::assertContains("My value", $metadataValue);
        self::assertContains("My second value", $metadataValue);
        self::assertContains("My third value", $metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My other label");
        self::assertTrue(is_array($metadataValue));
        self::assertContains("My other value", $metadataValue);
        self::assertContains("My second other value", $metadataValue);
        self::assertContains("My third other value", $metadataValue);
    }
    
    public function testFromArray()
    {
        $mockArray[Names::ID] = 'http://www.example.com/mockresource/id';
        $mockArray[Names::METADATA] = json_decode('[{"label": "My label", "value": "My value"}, {"label": "My other label", "value": "My other value"}]', true);
        $mockResource = MockIiifResource::fromArray($mockArray);
        
        $metadata = $mockResource->getMetadata();
        self::assertNotNull($metadata);
        self::assertTrue(is_array($metadata));

        $valueForLabel1 = $mockResource->getMetadataForLabel('My label');
        self::assertNotNull($valueForLabel1);
        self::assertEquals('My value', $valueForLabel1);
        
        $valueForLabel2 = $mockResource->getMetadataForLabel('My other label');
        self::assertNotNull($valueForLabel2);
        self::assertEquals('My other value', $valueForLabel2);
    }
    
    private function prepareMetadata($metadataString)
    {
        $metadata = json_decode($metadataString, true);
        $this->abstractIiifResource->setMetadata($metadata);
    }
    private function prepareLabel($labelString)
    {
        $label = json_decode($labelString, true);
        $label = $label==null?$labelString:$label;
        $this->abstractIiifResource->setLabel($label);
        
    }
}
