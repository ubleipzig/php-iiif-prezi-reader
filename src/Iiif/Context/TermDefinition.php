<?php
/*
 * Copyright (C) 2019 Leipzig University Library <info@ub.uni-leipzig.de>
 * 
 * This file is part of the php-iiif-prezi-reader.
 * 
 * php-iiif-prezi-reader is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Ubl\Iiif\Context;

/**
 * Represents a JSON-LD term definition.
 * 
 * @author lutzhelm
 *
 * @link https://w3c.github.io/json-ld-api/#dfn-term-definition
 */
class TermDefinition {

    /**
     * @var string
     */
    protected $term;

    /**
     * Indicate wether the term is only used as a prefix of a compact IRI
     * 
     * @var boolean
     */
    protected $prefix;
    
    /**
     * @var boolean
     */
    protected $protected_;

    /**
     * @var string
     */
    protected $indexMapping;
    
    /**
     * @var string
     */
    protected $iriMapping;

    /**
     * @var string
     */
    protected $typeMapping;

    /**
     * @var string
     */
    protected $languageMapping;

    /**
     * @var string
     */
    protected $nestValue;

    /**
     * @var string
     */
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
    protected $localContext;

    /**
     *
     * @return string
     */
    public function getTerm() {
        return $this->term;
    }

    /**
     *
     * @return JsonLdContext
     */
    public function getLocalContext() {
        return $this->localContext;
    }

    /**
     * @return string
     */
    public function getIndexMapping() {
        return $this->indexMapping;
    }

    /**
     * @param string $indexMapping
     */
    public function setIndexMapping($indexMapping) {
        $this->indexMapping = $indexMapping;
    }

    /**
     *
     * @return string
     */
    public function getIriMapping() {
        return $this->iriMapping;
    }

    /**
     *
     * @param string $iriMapping
     */
    public function setIriMapping($iriMapping) {
        $this->iriMapping = $iriMapping;
    }

    /**
     *
     * @return string
     */
    public function getTypeMapping() {
        return $this->typeMapping;
    }

    /**
     *
     * @param string $typeMapping
     */
    public function setTypeMapping($typeMapping) {
        $this->typeMapping = $typeMapping;
    }

    /**
     *
     * @return string
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
     * @return string
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
     * @param string $languageMapping
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
     * @param string $containerMapping
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
     * @param \Ubl\Iiif\Context\JsonLdContext $localContext
     */
    public function setLocalContext($localContext) {
        $this->localContext = $localContext;
    }

    /**
     *
     * @return boolean
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     *
     * @param boolean $prefix
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    /**
     * @return boolean
     */
    public function getProtected_() {
        return $this->protected_;
    }

    /**
     * @param boolean $protected_
     */
    public function setProtected_($protected_) {
        $this->protected_ = $protected_;
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
    
    public function __construct($term = null) {
        $this->$term = $term;
    }

    private function hasContainer($keyword) {
        return $this->containerMapping != null && ((is_string($this->containerMapping) && $keyword == $this->containerMapping) || (is_array($this->containerMapping) && array_search($keyword, $this->containerMapping) !== false));
    }
}

