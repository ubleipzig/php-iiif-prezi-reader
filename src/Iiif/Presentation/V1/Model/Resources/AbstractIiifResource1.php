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

namespace Ubl\Iiif\Presentation\V1\Model\Resources;

use Ubl\Iiif\Context\JsonLdHelper;
use Ubl\Iiif\Context\Keywords;
use Ubl\Iiif\Presentation\Common\Model\Resources\AbstractIiifResource;
use Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface;
use Ubl\Iiif\Presentation\Common\TypeMap;
use Ubl\Iiif\Services\Service;

abstract class AbstractIiifResource1 extends AbstractIiifResource implements IiifResourceInterface {

    /**
     * 
     * @var string
     */
    protected $id;
    /**
     *
     * @var string
     */
    protected $type;
    
    /**
     *
     * @var string|array
     */
    protected $label;
    
    /**
     * 
     * @var string|array
     */
    protected $attribution;

    /**
     *
     * @var string|array
     */
    protected $license;

    /**
     *
     * @var string|array
     */
    protected $related;

    /**
     * 
     * @var string|array|\Ubl\Iiif\Services\Service
     */
    protected $service;
    
    /**
     * 
     * @var string|array
     */
    protected $seeAlso;

    /**
     * 
     * @var AbstractIiifResource1
     */
    protected $within;

    

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity::getValueForTypelessProperty()
     */
    protected function getValueForTypelessProperty($property, $dictionary, \Ubl\Iiif\Context\JsonLdContext $context) {
        if ($property = "service") {
            $idOrAlias = $context->getKeywordOrAlias(Keywords::ID);
            $clazz = null;
            if ($this instanceof ContentResource1 && $context->expandIRI($this->getType()) == "http://purl.org/dc/dcmitype/Image") {
                $contextOrAlias = $context->getKeywordOrAlias(Keywords::CONTEXT);
                if (array_key_exists($contextOrAlias, $dictionary) && array_key_exists($dictionary[$contextOrAlias], TypeMap::SERVICE_TYPES_BY_CONTEXT)) {
                    $clazz = TypeMap::getClassForType(TypeMap::SERVICE_TYPES_BY_CONTEXT[$dictionary[$contextOrAlias]], $context);
                } elseif (array_key_exists("profile", $dictionary) && array_key_exists($dictionary["profile"], TypeMap::SERVICE_TYPES_BY_PROFILE)) {
                    $clazz = TypeMap::getClassForType(TypeMap::SERVICE_TYPES_BY_PROFILE[$dictionary["profile"]], $context);
                }
            }
            $id = array_key_exists($idOrAlias, $dictionary) ? $dictionary[$idOrAlias] : null;
            $profile = array_key_exists("profile", $dictionary) ? $dictionary["profile"] : null;
            $service = $clazz == null ? new Service($id, $profile) : new $clazz($id, $profile);
            return $service;
        }
    }
    
    

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity::getTypelessProperties()
     */
    protected function getTypelessProperties() {
        return [
            "service"
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getId()
     */
    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getLabel()
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getLabelForDisplay()
     */
    public function getLabelForDisplay($language = null, $joinChar = "; ") {
        return $this->getValueForDisplay($this->label, $language, $joinChar);
    }

    /**
     * In contrast to later versions of the Presentation API, not all resources of the Metadata API may have metadata. 
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getMetadata()
     */
    public function getMetadata() {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getMetadataForDisplay()
     */
    public function getMetadataForDisplay($language = null, $joinChars = "; ", $options = 0) {
        /*
         *  Metadata API https://iiif.io/api/metadata/1.0/#md-requirements
         *  - only manifests, sequences, canvases, ranges and layers may have metadata
         *  - see AbstractDescribableResource1
         */ 
        return null;
    }
    
    public function getRelated() {
        return $this->related;
    }

    /**
     * Not supported by IIIF Metadata API
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getRendering()
     */
    public function getRendering() {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getRenderingUrlsForFormat()
     */
    public function getRenderingUrlsForFormat($format, $useChildResources = true) {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getRequiredStatement()
     */
    public function getRequiredStatement() {
        return $this->attribution;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getRequiredStatementForDisplay()
     */
    public function getRequiredStatementForDisplay($language = null, $joinChars = "; ", $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML) {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getRights()
     */
    public function getRights() {
        return $this->license;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getSeeAlso()
     */
    public function getSeeAlso() {
        return $this->seeAlso;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getSeeAlsoUrlsForFormat()
     */
    public function getSeeAlsoUrlsForFormat($format) {
        if (!is_array($this->seeAlso)) {
            return null;
        }
        $result = [];
        $seeAlso = JsonLdHelper::isSimpleArray($this->seeAlso) ? $this->seeAlso : [$this->seeAlso];
        foreach ($seeAlso as $candidate) {
            if (array_key_exists("format", $candidate)) {
                if ($format == $candidate["format"]) {
                    $result[] = $candidate["@id"];
                }
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getSeeAlsoUrlsForProfile()
     */
    public function getSeeAlsoUrlsForProfile($profile, $startsWith = false) {
        if (!is_array($this->seeAlso)) {
            return null;
        }
        $seeAlso = JsonLdHelper::isSimpleArray($this->seeAlso) ? $this->seeAlso : [$this->seeAlso];
        $result = [];
        foreach ($seeAlso as $candidate) {
            if (array_key_exists("profile", $candidate)) {
                if (is_string($candidate["profile"])) {
                    if ($candidate["profile"] == $profile || ($startsWith && strpos($candidate["profile"], $profile)===0)) {
                        $result[] = $candidate["@id"];
                    }
                } elseif (JsonLdHelper::isSimpleArray($candidate["profile"])) {
                    foreach ($candidate["profile"] as $profileItem) {
                        if (is_string($profileItem) && ($profileItem == $profile || ($startsWith && strpos($profileItem, $profile)===0))) {
                            $result[] = $candidate["@id"];
                            break;
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getService()
     */
    public function getService() {
        return $this->service;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getSingleService()
     */
    public function getSingleService() {
        if (empty($this->service)) {
            return null;
        }
        if (JsonLdHelper::isSimpleArray($this->service)) {
            return $this->service[0];
        }
        return $this->service; 
    }

    /**
     * In contrast to later versions of the Presentation API, not all resources of the Metadata API may have a summary. 
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getSummary()
     */
    public function getSummary() {
        return null;
    }

    /**
     * In contrast to later versions of the Presentation API, not all resources of the Metadata API may have a summary. 
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getSummaryForDisplay()
     */
    public function getSummaryForDisplay($language = null, $joinChars = "; ", $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML) {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        return null;
    }

    protected function getValueForDisplay($value, $language = null, $joinChars = "; ", $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML) {
        if (is_null($value)){
            return null;
        }
        if (is_string($value)){
            if ($options&(IiifResourceInterface::SANITIZE_XML_ENCODE_ALL|IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML)) {
                $value = htmlspecialchars($value,  ENT_QUOTES);
            }
            return $value;
        }
        if (is_array($value)) {
            if (!JsonLdHelper::isSimpleArray($value)) {
                $value = [$value];
            }
            $defaultLanguage = null;
            $defaultLanguageArray = [];
            $noLanguageArray = [];
            $requestedLanguageArray = [];
            foreach ($value as $entry){
                if (is_string($entry)) {
                    $noLanguageArray[] = $entry;
                } elseif (array_key_exists(Keywords::LANGUAGE, $entry) && array_key_exists(Keywords::VALUE, $entry)) {
                    if ($language == $entry[Keywords::LANGUAGE]) {
                        $entryValue = $entry[Keywords::VALUE];
                        if ($options&(IiifResourceInterface::SANITIZE_XML_ENCODE_ALL|IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML)) {
                            $entryValue = htmlspecialchars($entryValue,  ENT_QUOTES);
                        }
                        $requestedLanguageArray[] = $entryValue;
                    } elseif (empty($requestedLanguageArray)) {
                        if (!isset($defaultLanguage)) {
                            $defaultLanguage = $entry[Keywords::LANGUAGE];
                        }
                        if ($defaultLanguage == $entry[Keywords::LANGUAGE]) {
                            $entryValue = $entry[Keywords::VALUE];
                            if ($options&(IiifResourceInterface::SANITIZE_XML_ENCODE_ALL|IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML)) {
                                $entryValue = htmlspecialchars($entryValue,  ENT_QUOTES);
                            }
                            $defaultLanguageArray[] = $entryValue;
                        }
                    }
                }
            }
            $resultArray = !empty($requestedLanguageArray) ? $requestedLanguageArray : (!empty($noLanguageArray) ? $noLanguageArray : $defaultLanguageArray);
            if (isset($joinChars)) {
                return implode($joinChars, $resultArray);
            }
            return $resultArray;
        }
        return null;
    }
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getWeblinksForDisplay()
     */
    public function getWeblinksForDisplay($language = null, $joinChars = " ") {
        // TODO untested
        return $this->getWeblinksForDisplayCommon($this->related, $language, $joinChars, "@id");
    }

}