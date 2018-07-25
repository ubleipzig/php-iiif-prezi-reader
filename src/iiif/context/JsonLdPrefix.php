<?php
namespace iiif\context;

class JsonLdPrefix
{
    protected $prefix;
    protected $baseUrl;
    
    public function __contruct($prefix = null, $baseUrl = null) {
        $this->prefix = $prefix;
        $this->baseUrl = $baseUrl;
    }
    
    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
    
    public function expandIri($suffix) {
        return $baseUrl.$suffix;
    }
    
    public function compactIri($iri) {
        return str_replace($baseUrl, $prefix.':', $iri);
    }

}

