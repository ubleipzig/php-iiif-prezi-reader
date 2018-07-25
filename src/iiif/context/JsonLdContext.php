<?php
namespace iiif\context;

class JsonLdContext
{
    protected $contextUri;
    protected $prefixes = array();
    protected $propertyTerms;
    protected $resourceTypes;
    protected $contexts;
    protected $version;
    
    public function expandIRI($toExpand) {
        if (!IRI::isCompressedUri($toExpand)) return $toExpand;
    }
    
    public function addPrefix(JsonLdPrefix $prefix) {
        $this->prefixes[$prefix->getPrefix()] = $prefix;
    }
}

