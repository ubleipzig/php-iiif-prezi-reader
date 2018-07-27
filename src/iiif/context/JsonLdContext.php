<?php
namespace iiif\context;

class JsonLdContext
{
    protected $baseIri;
    protected $prefixes = array();
    protected $propertyTerms = array();
    protected $vocabulary = array();
    protected $resourceTypes;
    protected $contexts = array();
    protected $version;

    public function expandIRI($toExpand) {
        if (!IRI::isCompressedUri($toExpand)) return $toExpand;
    }
    
    public function addPrefix(JsonLdPrefix $prefix) {
        $this->prefixes[$prefix->getPrefix()] = $prefix;
    }
    
    public function addPropertyTerm(Term $term) {
        $this->propertyTerms[$term->getTerm()] = $term;
        if ($term->getContext()!=null) $this->contexts[$term->getContext()->getBaseIri()] = $term->getContext();
    }
    
    public function addVocabularyEntry(Term $term) {
        $this->vocabulary[$term->getTerm()] = $term;
    }
    
    public function addContext(JsonLdContext $context) {
        $this->contexts[$context->getBaseIri()] = $context;
    }
    
    public function getBaseIri() {
        return $this->baseIri;
    }
    
    /**
     * @param string $baseIri
     */
    public function setBaseIri($baseIri)
    {
        $this->baseIri = $baseIri;
    }
    /**
     * @return multitype:
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * @return multitype:
     */
    public function getPropertyTerms()
    {
        return $this->propertyTerms;
    }

    /**
     * @return multitype:
     */
    public function getVocabulary()
    {
        return $this->vocabulary;
    }

    /**
     * @return mixed
     */
    public function getResourceTypes()
    {
        return $this->resourceTypes;
    }

    /**
     * @return multitype:
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    public function clone() {
        // TODO
        return null;
    }

    
}

