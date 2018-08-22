<?php
namespace iiif\context;

class JsonLdContext
{
    protected $baseIri = "";
    protected $termDefinitions = array();
    protected $vocabularyMapping;
    protected $defaultLanguage;

    public function expandIRI($toExpand) {
        if (!IRI::isCompressedUri($toExpand)) return $toExpand;
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
    public function clone() {
        // TODO
        $clone = new JsonLdContext();
        foreach ($this->termDefinitions as $term => $definition) {
            $clone->addTermDefinition($term, $definition);
        }
        $clone->setVocabularyMapping($this->getVocabularyMapping());
        $clone->setBaseIri($this->getBaseIri());
        return $clone;
    }


    public function addTermDefinition($term, $definition) {
        $this->termDefinitions[$term] = $definition;
    }
    
    public function removeTermDefinition($term) {
        unset($this->termDefinitions[$term]);
    }
    
    /**
     * @param string $term
     * @return TermDefinition
     */
    public function getTermDefinition($term) {
        if (!array_key_exists($term, $this->termDefinitions)) return null;
        return $this->termDefinitions[$term];
    }
    /**
     * @return mixed
     */
    public function getVocabularyMapping()
    {
        return $this->vocabularyMapping;
    }

    /**
     * @param mixed $vocabularyMapping
     */
    public function setVocabularyMapping($vocabularyMapping)
    {
        $this->vocabularyMapping = $vocabularyMapping;
    }
    /**
     * @return mixed
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    /**
     * @param mixed $defaultLanguage
     */
    public function setDefaultLanguage($defaultLanguage)
    {
        $this->defaultLanguage = $defaultLanguage;
    }



}

