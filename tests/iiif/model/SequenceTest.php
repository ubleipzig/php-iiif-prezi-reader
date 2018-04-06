<?php
use iiif\model\resources\Sequence;

require_once 'iiif/model/resources/Sequence.php';

/**
 * Sequence test case.
 */
class SequenceTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Sequence
     */
    private $sequence;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated SequenceTest::setUp()
        
        $this->sequence = new Sequence(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated SequenceTest::tearDown()
        $this->sequence = null;
        
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
     * Tests Sequence::fromArray()
     */
    public function testFromArray()
    {
        // TODO Auto-generated SequenceTest::testFromArray()
        $this->markTestIncomplete("fromArray test not implemented");
        
        Sequence::fromArray(/* parameters */);
    }

    /**
     * Tests Sequence->getCanvases()
     */
    public function testGetCanvases()
    {
        // TODO Auto-generated SequenceTest->testGetCanvases()
        $this->markTestIncomplete("getCanvases test not implemented");
        
        $this->sequence->getCanvases(/* parameters */);
    }

    /**
     * Tests Sequence->getStartCanvasOrFirstCanvas()
     */
    public function testGetStartCanvasOrFirstCanvas()
    {
        // TODO Auto-generated SequenceTest->testGetStartCanvasOrFirstCanvas()
        $this->markTestIncomplete("getStartCanvasOrFirstCanvas test not implemented");
        
        $this->sequence->getStartCanvasOrFirstCanvas(/* parameters */);
    }
}

