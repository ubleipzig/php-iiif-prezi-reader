<?php
namespace iiif\context;

class TermDefinition {

    protected $term;

    protected $prefix;

    protected $iriMapping;

    protected $typeMapping;

    protected $languageMapping;

    protected $nestValue;

    protected $containerMapping;

    /**
     *
     * @var boolean
     */
    protected $reverse;

    /**
     * Optional context
     *
     * @var JsonLdContext
     */
    protected $context;

    protected $localContext;

    /**
     *
     * @return mixed
     */
    public function getTerm() {
        return $this->term;
    }

    /**
     *
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     *
     * @return mixed
     */
    public function getExpandedId() {
        return $this->expandedId;
    }

    /**
     *
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     * @return mixed
     */
    public function getLanuage() {
        return $this->lanuage;
    }

    /**
     *
     * @return mixed
     */
    public function getCollectionContainer() {
        return $this->collectionContainer;
    }

    /**
     *
     * @return JsonLdContext
     */
    public function getContext() {
        return $this->context;
    }

    /**
     *
     * @return mixed
     */
    public function getIriMapping() {
        return $this->iriMapping;
    }

    /**
     *
     * @param mixed $iriMapping
     */
    public function setIriMapping($iriMapping) {
        $this->iriMapping = $iriMapping;
    }

    /**
     *
     * @return mixed
     */
    public function getTypeMapping() {
        return $this->typeMapping;
    }

    /**
     *
     * @param mixed $typeMapping
     */
    public function setTypeMapping($typeMapping) {
        $this->typeMapping = $typeMapping;
    }

    /**
     *
     * @return mixed
     */
    public function getLanguageMapping() {
        return $this->languageMapping;
    }

    /**
     *
     * @return mixed
     */
    public function getNestValue() {
        return $this->nestValue;
    }

    /**
     *
     * @return mixed
     */
    public function getContainerMapping() {
        return $this->containerMapping;
    }

    /**
     *
     * @return boolean
     */
    public function isReverse() {
        return $this->reverse;
    }

    /**
     *
     * @param mixed $languageMapping
     */
    public function setLanguageMapping($languageMapping) {
        $this->languageMapping = $languageMapping;
    }

    /**
     *
     * @param mixed $nestValue
     */
    public function setNestValue($nestValue) {
        $this->nestValue = $nestValue;
    }

    /**
     *
     * @param mixed $containerMapping
     */
    public function setContainerMapping($containerMapping) {
        $this->containerMapping = $containerMapping;
    }

    /**
     *
     * @param boolean $reverse
     */
    public function setReverse($reverse) {
        $this->reverse = $reverse;
    }

    /**
     *
     * @param \iiif\context\JsonLdContext $context
     */
    public function setContext($context) {
        $this->context = $context;
    }

    /**
     *
     * @return mixed
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     *
     * @param mixed $prefix
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function hasSetContainer() {
        return $this->hasContainer(Keywords::SET);
    }

    public function hasLanguageContainer() {
        return $this->hasContainer(Keywords::LANGUAGE);
    }

    public function hasListContainer() {
        return $this->hasContainer(Keywords::LIST_);
    }

    private function hasContainer($keyword) {
        return $this->containerMapping != null && ((is_string($this->containerMapping) && $keyword == $this->containerMapping) || (is_array($this->containerMapping) && array_search($keyword, $this->containerMapping) !== false));
    }
}

