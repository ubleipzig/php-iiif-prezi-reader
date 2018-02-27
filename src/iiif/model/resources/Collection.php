<?php
namespace iiif\model\resources;

use iiif\model\properties\NavDateTrait;
use iiif\model\vocabulary\Names;

class Collection extends AbstractIiifResource
{
    use NavDateTrait;
    
    const TYPE="sc:Collection";
    
    protected $navDate;
    
    /**
     * 
     * @var Collection[]
     * @deprecated IIIF Presentation API doc: "The collections and manifests properties are likely to be removed in version 3.0 in favor of the single members property."
     */
    protected $collections = array();
    /**
     * 
     * @var Manifest[]
     * @deprecated IIIF Presentation API doc: "The collections and manifests properties are likely to be removed in version 3.0 in favor of the single members property."
     */
    protected $manifests = array(); 
    protected $members =array();
    /**
     * {@inheritDoc}
     * @see \iiif\model\resources\AbstractIiifResource::fromArray()
     */
    public static function fromArray($jsonAsArray)
    {
        $collection=new Collection();
        $collection->loadPropertiesFromArray($jsonAsArray);
        $collection->loadResources($jsonAsArray, Names::COLLECTIONS, Collection::class, $collection->collections);
        $collection->loadResources($jsonAsArray, Names::MANIFESTS, Manifest::class, $collection->manifests);
        // TODO: Members. Could be Collection or Manifest.
        return $collection;
    }

}

