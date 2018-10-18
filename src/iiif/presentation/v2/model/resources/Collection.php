<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\NavDateTrait;
use iiif\presentation\v2\model\vocabulary\Names;

class Collection extends AbstractIiifResource {
    use NavDateTrait;

    const TYPE = "sc:Collection";

    protected $navDate;

    /**
     *
     * @var Collection[]
     */
    protected $collections = array();

    /**
     *
     * @var Manifest[]
     */
    protected $manifests = array();

    protected $members = array();

    /**
     *
     * {@inheritdoc}
     * @see \iiif\presentation\v2\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray, &$allResources = array()) {
        $collection = self::createInstanceFromArray($jsonAsArray, $allResources);
        $collection->loadPropertiesFromArray($jsonAsArray, $allResources);
        $collection->loadResources($jsonAsArray, Names::COLLECTIONS, Collection::class, $collection->collections, $allResources);
        $collection->loadResources($jsonAsArray, Names::MANIFESTS, Manifest::class, $collection->manifests, $allResources);
        // TODO: Members. Could be Collection or Manifest.
        return $collection;
    }
}

