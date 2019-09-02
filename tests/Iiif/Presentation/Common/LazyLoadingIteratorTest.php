<?php
use Ubl\Iiif\AbstractIiifTest;
use Ubl\Iiif\Presentation\Common\Model\LazyLoadingIterator;
use Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity;
use Ubl\Iiif\Tools\IiifHelper;
use Ubl\Iiif\UrlReaderForTests;
use Ubl\Iiif\Presentation\V2\Model\Resources\AnnotationList2;

/**
 * LazyLoadingIterator test case.
 */
class LazyLoadingIteratorTest extends AbstractIiifTest {

    /**
     * @var LazyLoadingIterator $lazyLoadingIterator
     */
    private $lazyLoadingIterator;

    private $urlReader;

    public function setup() {
        $this->urlReader = new UrlReaderForTests();
        IiifHelper::setUrlReader($this->urlReader);
        $content = self::getFile('v2/manifest-for-lazy-annotations.json');
        /**
         * @var ManifestInterface $manifest
         */
        $manifest = AbstractIiifEntity::loadIiifResource($content);
        /**
         * @var \Ubl\Iiif\Presentation\V2\Model\Resources\Canvas2 $canvas
         */
        $canvas = $manifest->getDefaultCanvases()[0];

        $this->lazyLoadingIterator = $canvas->getPotentialTextAnnotationContainerIterator();
    }
    
    /**
     * Tests LazyLoadingIterator->valid(), LazyLoadingIterator->key(), LazyLoadingIterator->next()
     */
    public function testIterator() {
        self::assertEquals(true , $this->lazyLoadingIterator->valid());
        self::assertEquals(0, $this->lazyLoadingIterator->key());
        $this->lazyLoadingIterator->next();

        self::assertEquals(true , $this->lazyLoadingIterator->valid());
        self::assertEquals(1, $this->lazyLoadingIterator->key());
        $this->lazyLoadingIterator->next();
        
        self::assertEquals(false , $this->lazyLoadingIterator->valid());
        self::assertEquals(null, $this->lazyLoadingIterator->key());
    }

    /**
     * Tests LazyLoadingIterator->current(), LazyLoadingIterator->rewind()
     */
    public function testCurrent() {
        self::assertEquals(true , $this->lazyLoadingIterator->valid());
        self::assertEquals(0, $this->lazyLoadingIterator->key());
        /**
         * @var \Ubl\Iiif\Presentation\V2\Model\Resources\AnnotationList2 $annotationList
         */
        $annotationList = $this->lazyLoadingIterator->current();
        self::assertInstanceOf(AnnotationList2::class, $annotationList);
        self::assertEquals(true, $annotationList->isInitialized());
        self::assertEquals(2, sizeof($annotationList->getResources()));
        
        $this->lazyLoadingIterator->current();
        $this->lazyLoadingIterator->current();
        $this->lazyLoadingIterator->current();
        self::assertEquals(1, $this->urlReader->getRequestedUrls()['http://example.org/iiif/book1/list/list1']);

        $this->lazyLoadingIterator->next();
        $annotationList = $this->lazyLoadingIterator->current();
        self::assertInstanceOf(AnnotationList2::class, $annotationList);
        self::assertEquals(true, $annotationList->isInitialized());
        self::assertEquals(3, sizeof($annotationList->getResources()));

        $this->lazyLoadingIterator->next();
        self::assertNull($this->lazyLoadingIterator->current());

        $this->lazyLoadingIterator->rewind();
        $annotationList = $this->lazyLoadingIterator->current();
        self::assertInstanceOf(AnnotationList2::class, $annotationList);
        self::assertEquals(true, $annotationList->isInitialized());
        self::assertEquals(2, sizeof($annotationList->getResources()));
        self::assertEquals(1, $this->urlReader->getRequestedUrls()['http://example.org/iiif/book1/list/list1']);
    }

}

