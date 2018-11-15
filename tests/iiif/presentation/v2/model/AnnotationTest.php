<?php
use iiif\presentation\v2\model\resources\Annotation;
use iiif\presentation\v2\model\AbstractIiifTest;

/**
 * Annotation test case.
 */
class AnnotationTest extends AbstractIiifTest {

    /**
     *
     * @var Annotation
     */
    private $annotation;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp();
        
        // TODO Auto-generated AnnotationTest::setUp()
        
        $this->annotation = new Annotation();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        // TODO Auto-generated AnnotationTest::tearDown()
        $this->annotation = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct() {
        // TODO Auto-generated constructor
    }

    /**
     * Tests Annotation->getResource()
     */
    public function testGetResource() {
        // TODO Auto-generated AnnotationTest->testGetResource()
        $this->markTestIncomplete("getResource test not implemented");
        
        $this->annotation->getResource(/* parameters */);
    }

    /**
     * Tests Annotation->getOn()
     */
    public function testGetOn() {
        // TODO Auto-generated AnnotationTest->testGetOn()
        $this->markTestIncomplete("getOn test not implemented");
        
        $this->annotation->getOn(/* parameters */);
    }

    /**
     * Tests Annotation->getMotivation()
     */
    public function testGetMotivation() {
        // TODO Auto-generated AnnotationTest->testGetMotivation()
        $this->markTestIncomplete("getMotivation test not implemented");
        
        $this->annotation->getMotivation(/* parameters */);
    }
}

