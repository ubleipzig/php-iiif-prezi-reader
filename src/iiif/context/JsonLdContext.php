<?php
namespace iiif\context;

class JsonLdContext {

    protected $contextIri = "";

    protected $baseIri = "";

    protected $termDefinitions = array();

    protected $vocabularyMapping;

    protected $defaultLanguage;

    /**
     * Containes any terms that expand to json-ld keywords
     *
     * @var array
     */
    protected $keywordAliases = array();

    /**
     * 
     * @var JsonLdProcessor
     */
    protected $processor;
    
    public function expandIRI($toExpand) {
        if (!IRI::isCompactIri($toExpand, $this)) {
            return $toExpand;
        }
        return $this->processor->expandIRI($this, $toExpand);
    }

    public function getBaseIri() {
        return $this->baseIri;
    }

    /**
     *
     * @param string $baseIri
     */
    public function setBaseIri($baseIri) {
        $this->baseIri = $baseIri;
    }

    public function __clone() {
        $termDefinitions = [];
        foreach ($this->termDefinitions as $term => $definition) {
            // TODO do we really need cloned term definitions?
            $termDefinitions[$term] = clone $definition;
        }
        $this->termDefinitions = $termDefinitions;
    }

    public function addTermDefinition($term, $definition) {
        $this->termDefinitions[$term] = $definition;
    }

    public function removeTermDefinition($term) {
        unset($this->termDefinitions[$term]);
    }

    /**
     *
     * @param string $term
     * @return TermDefinition
     */
    public function getTermDefinition($term) {
        if (! array_key_exists($term, $this->termDefinitions))
            return null;
        return $this->termDefinitions[$term];
    }

    /**
     *
     * @return mixed
     */
    public function getVocabularyMapping() {
        return $this->vocabularyMapping;
    }

    /**
     *
     * @param mixed $vocabularyMapping
     */
    public function setVocabularyMapping($vocabularyMapping) {
        $this->vocabularyMapping = $vocabularyMapping;
    }

    /**
     *
     * @return mixed
     */
    public function getDefaultLanguage() {
        return $this->defaultLanguage;
    }

    /**
     *
     * @param mixed $defaultLanguage
     */
    public function setDefaultLanguage($defaultLanguage) {
        $this->defaultLanguage = $defaultLanguage;
    }

    public function addKeywordAlias($keyword, $alias) {
        $this->keywordAliases[$keyword] = $alias;
    }

    public function getKeywordOrAlias($keyword) {
        if (array_key_exists($keyword, $this->keywordAliases)) {
            return $this->keywordAliases[$keyword];
        }
        return $keyword;
    }

    /**
     *
     * @return string
     */
    public function getContextIri() {
        return $this->contextIri;
    }

    /**
     *
     * @param string $contextIri
     */
    public function setContextIri($contextIri) {
        $this->contextIri = $contextIri;
    }
    
    public function __construct(JsonLdProcessor $processor) {
        $this->processor = $processor;
    }
}

