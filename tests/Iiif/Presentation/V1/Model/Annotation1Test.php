<?php
namespace Presentation\V1\Model;

use Ubl\Iiif\AbstractIiifTest;
use Ubl\Iiif\Tools\IiifHelper;
use Ubl\Iiif\Presentation\Common\Model\XYWHFragment;
use Ubl\Iiif\Presentation\V1\Model\Resources\Canvas1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Annotation1;

class Annotation1Test extends AbstractIiifTest {

    public function testGetOnSelector() {
        /**
         * @var \Ubl\Iiif\Presentation\V1\Model\Resources\Canvas1 $canvas
         */
        $canvas = IiifHelper::loadIiifResource(parent::getFile("v1/canvas-with-annotations.json"));
        self::assertInstanceOf(Canvas1::class, $canvas);
        $images = $canvas->getImageAnnotations();
        self::assertEquals(3, sizeof($images));
        $annotation1 = $images[0];
        self::assertInstanceOf(Annotation1::class, $annotation1);
        self::assertNull($annotation1->getOnSelector());
        $annotation2 = $images[2];
        self::assertInstanceOf(Annotation1::class, $annotation1);
        self::assertNotNull($annotation2->getOnSelector());
        self::assertInstanceOf(XYWHFragment::class, $annotation2->getOnSelector());
    }
    
    public function testGetTargetResourceId() {
        /**
         * @var \Ubl\Iiif\Presentation\V1\Model\Resources\Canvas1 $canvas
         */
        $canvas = IiifHelper::loadIiifResource(parent::getFile("v1/canvas-with-annotations.json"));
        $images = $canvas->getImageAnnotations();
        $annotation1 = $images[0];
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $annotation1->getTargetResourceId());
        $annotation2 = $images[2];
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $annotation2->getTargetResourceId());
    }
}

