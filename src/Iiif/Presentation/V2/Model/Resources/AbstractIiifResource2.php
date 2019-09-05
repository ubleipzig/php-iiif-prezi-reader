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

namespace Ubl\Iiif\Presentation\V2\Model\Resources;

use Ubl\Iiif\Context\JsonLdHelper;
use Ubl\Iiif\Context\Keywords;
use Ubl\Iiif\Presentation\Common\TypeHelper;
use Ubl\Iiif\Presentation\Common\TypeMap;
use Ubl\Iiif\Presentation\Common\Model\LazyLoadingIterator;
use Ubl\Iiif\Presentation\Common\Model\Resources\AbstractIiifResource;
use Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface;
use Ubl\Iiif\Services\AbstractImageService;
use Ubl\Iiif\Services\Profile;
use Ubl\Iiif\Services\Service;
use Ubl\Iiif\Tools\IiifHelper;
use Ubl\Iiif\Tools\Options;

/**
 * Bundles all resource properties that every single iiif resource type may have
 * see http://iiif.io/api/presentation/2.1/#resource-properties
 *
 * @author lutzhelm
 *
 */
abstract class AbstractIiifResource2 extends AbstractIiifResource implements IiifResourceInterface {

    // http://iiif.io/api/presentation/2.1/#technical-properties
    /**
     *
     * @var string
     */
    protected $id;

    protected $viewingHint;

    // http://iiif.io/api/presentation/2.1/#descriptive-properties
    protected $label;

    protected $metadata;

    protected $description;

    /**
     *
     * @var ContentResource2
     */
    protected $thumbnail;

    // http://iiif.io/api/presentation/2.1/#rights-and-licensing-properties
    protected $attribution;

    protected $license;

    protected $logo;

    // http://iiif.io/api/presentation/2.1/#linking-properties
    protected $related;

    protected $rendering;

    /**
     *
     * @var Service
     */
    protected $service;

    protected $seeAlso;

    protected $within;

    protected $preferredLanguage;

    // keep it for faster and easier searches
    protected $originalJsonArray;

    protected $originalJson;

    /**
     *
     * @var boolean
     */
    protected $reference;

    protected function getTypelessProperties() {
        return [
            "service"
        ];
    }
    
    protected function getCollectionProperties() {
        return [
            "profile",
            "rendering",
            "seeAlso",
            "related"
        ];
    }

    /**
     *
     * {@inheritdoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity::getValueForSpecialProperty()
     */
    protected function getValueForTypelessProperty($property, $dictionary, $context) {
        if ($property = "service") {
            $idOrAlias = TypeHelper::getKeywordOrAlias($context, Keywords::ID);
            $clazz = null;
            if ($this instanceof ContentResource2 && $this->getType() == "sc:Image") {
                $contextOrAlias = TypeHelper::getKeywordOrAlias($context, Keywords::CONTEXT);
                if (array_key_exists($contextOrAlias, $dictionary) && array_key_exists($dictionary[$contextOrAlias], TypeHelper::CONTEXT_TYPES)) {
                    $clazz = TypeHelper::getClass($dictionary, $context);
                }
            }
            $id = array_key_exists($idOrAlias, $dictionary) ? $dictionary[$idOrAlias] : null;
            $profile = array_key_exists("profile", $dictionary) ? $dictionary["profile"] : null;
            $width = array_key_exists("width", $dictionary) ? $dictionary["width"] : null;
            $height = array_key_exists("height", $dictionary) ? $dictionary["height"] : null;
            $sizes = array_key_exists("sizes", $dictionary) ? $dictionary["sizes"] : null;
            $tiles = array_key_exists("tiles", $dictionary) ? $dictionary["tiles"] : null;
            $service = $clazz == null ? new Service($id, $profile) : new $clazz($id, $profile, $width, $height, $sizes, $tiles);
            return $service;
        }
    }

    protected function getValueForDisplay($value, $language = null, $joinChars = "; ", $html = false, $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML) {
        if (is_null($value)){
            return null;
        }
        if (is_string($value)){
            if ($html) {
                $value = $this->sanitizeHtml($value, $options);
            } elseif ($options&(IiifResourceInterface::SANITIZE_XML_ENCODE_ALL|IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML)) {
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
                        $requestedLanguageArray[] = $entry[Keywords::VALUE];
                    } elseif (empty($requestedLanguageArray)) {
                        if (!isset($defaultLanguage)) {
                            $defaultLanguage = $entry[Keywords::LANGUAGE];
                        }
                        if ($defaultLanguage == $entry[Keywords::LANGUAGE]) {
                            $entryValue = $entry[Keywords::VALUE];
                            if ($html) {
                                $entryValue = $this->sanitizeHtml($entryValue, $options);
                            } elseif ($options&(IiifResourceInterface::SANITIZE_XML_ENCODE_ALL|IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML)) {
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

    protected function getContainedResources() {}

    /**
     *
     * @return boolean
     */
    public function isReference() {
        return $this->reference;
    }

    /**
     *
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     * @return \Ubl\Iiif\Services\Service
     */
    public function getService() {
        return $this->service;
    }

    public function getServiceIterator() {
        return new LazyLoadingIterator($this, "service");
    }

    /**
     *
     * @return \Ubl\Iiif\Presentation\V2\Model\Resources\ContentResource2
     */
    public function getThumbnail() {
        return $this->thumbnail;
    }

    /**
     *
     * @return mixed
     */
    public function getViewingHint() {
        return $this->viewingHint;
    }

    /**
     *
     * @return mixed
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     *
     * @return mixed
     */
    public function getMetadata() {
        return $this->metadata;
    }

    /**
     *
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     *
     * @return mixed
     */
    public function getOriginalJsonArray() {
        return $this->originalJsonArray;
    }

    /**
     * @return mixed
     */
    public function getSeeAlso() {
        return $this->seeAlso;
    }
    
    public function getRendering() {
        return $this->rendering;
    }

    public function getLabelForDisplay($language = null, $joinChars = "; ") {
        return $this->getValueForDisplay($this->label, $language, $joinChars);
    }
    
    
    private function getMetadataByLabel($requestedLabel, $language = null) {
//         if (empty($this->metadata) || !is_array($this->metadata) || empty($requestedLabel)) {
//             return null;
//         }
//         $candidates = [];
//         $requestedLabelArray = is_array($requestedLabel) ? $requestedLabel : [$requestedLabel];
//         foreach ($this->metadata as $metadata) {
//             if (empty($metadata) || !is_array($metadata) || !array_key_exists("label", $metadata)) {
//                 continue;
//             }
//             $entryLabel = $metadata["label"];
//             // both are string or the param is the exact same array
//             if ($entryLabel == $requestedLabel) {
//                 $candidates[] = $metadata;
//                 continue;
//             }
//             if (is_string($entryLabel)) {
//                 continue;
//             }
//             if (JsonLdHelper::isDictionary($entryLabel)) {
//                 $entryLabel = [$entryLabel];
//             }
//             foreach ($entryLabel as $entryLabelMember) {
                
//             }
//         }
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getMetadataForDisplay()
     */
    public function getMetadataForDisplay($language = null, $joinChars = "; ", $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML) {
        if (!isset($this->metadata) || !JsonLdHelper::isSimpleArray($this->metadata)) {
            return null;
        }
        $result = null;
        foreach ($this->metadata as $metadata) {
            $resultData = [];
            if (array_key_exists("label", $metadata)) {
                $resultData["label"] = $this->getValueForDisplay($metadata["label"], $language, $joinChars, false, IiifResourceInterface::SANITIZE_XML_ENCODE_ALL);
            } else {
                $resultData["label"] = "";
            }
            if (array_key_exists("value", $metadata)) {
                $resultData["value"] = $this->getValueForDisplay($metadata["value"], $language, $joinChars, true, $options);
            } else {
                $resultData["value"] = "";
            }
            $result[] = $resultData;
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getMetadataLabelForDisplay()
     */
    public function getMetadataLabelForDisplay($label, $language = null, $joinChars = "; ", $switchToExistingLanguage = true) {
        // TODO Auto-generated method stub
        /*
         * label is string (single value)
         * label is sequential array (multiple values or multiple languages or both)
         */
        if (empty($label) || empty($this->getMetadata())) {
            return null;
        }
        if (is_string($label)) {
            
        }
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getMetadataValueByLabelForDisplay()
     */
    public function getMetadataValueByLabelForDisplay($label, $language = null, $joinChars = "; ", $switchToExistingLanguage = true) {
        // TODO Auto-generated method stub
        
        /*
         * value is string
         * value is sequential array (multiple values or multiple languages or both)
         * 
         */
        
    }

    /**
     *
     * @param string $label
     * @param string $language
     * @return string value for given metadata label; preferably in the same language as the label if
     *         no language is given; first language language is provided by neither the $language paramater
     *         nor the label.
     */
    public function getMetadataForLabel($label, $language = null) {
        /*
         * ALL teh possibilities!
         * [{"label": {"@value": "Example label", "@language": "en"}, "value": {"@value": "Example value", "@language": "en"}}]
         * [{"label": "Example label", "value": "Example value"}]
         * ... and combinations with language/translation info either in the label or in the value.
         *
         * Presentation API 3 will fortunately change the structure: http://prezi3.iiif.io/api/presentation/3.0/#language-of-property-values
         */
        if ($this->metadata == null || $label == null)
            return null;
        $requestedValue = null;
        $targetLanguage = $language == null ? null : $language;
        foreach ($this->metadata as $metadatum) {
            if (is_string($metadatum["label"])) {
                if ($label == $metadatum["label"]) {
                    $requestedValue = $metadatum["value"];
                    break;
                }
            } elseif (is_array($metadatum["label"])) {
                foreach ($metadatum["label"] as $translatedLabel) {
                    if ($label == $translatedLabel[Keywords::VALUE]) {
                        $requestedValue = $requestedValue == null ? $metadatum["value"] : $requestedValue;
                        if ($targetLanguage == null) {
                            $targetLanguage = $translatedLabel[Keywords::LANGUAGE];
                        }
                        if ($language != null && $translatedLabel[Keywords::LANGUAGE] == $language) {
                            $requestedValue = $metadatum["value"];
                            break 2;
                        }
                    }
                }
            }
        }
        if ($requestedValue == null) {
            return null;
        }
        if (is_string($requestedValue)) {
            return $requestedValue;
        }
        if (is_array($requestedValue)) {
            if (is_array($requestedValue[0])) {
                $firstValue = null;
                foreach ($requestedValue as $translatedValue) {
                    if ($translatedValue[Keywords::LANGUAGE] == $targetLanguage) {
                        return $translatedValue[Keywords::VALUE];
                    }
                    if ($firstValue == null) {
                        $firstValue = $translatedValue[Keywords::VALUE];
                    }
                }
                return $firstValue;
            }
            return $requestedValue;
        }
        // this shouldn't happen
        return null;
    }

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
    
    public function getSingleService() {
        return $this->service == null ? null :
            JsonLdHelper::isSimpleArray($this->service) ? $this->service[0]:
            $this->service;
    }
    
    /**
     * Get the URL of any resource linked in the "rendering" property for a given format.
     * @param string $format format as media type (i.e. MIME type)
     * @param bool $useChildResources If set to true and the resource has not rendering URL for the requested format, the method will also look
     * for suitable rendering URLs in child resources where it might be reasonable that a rendering of the child could adequately represent
     * that resource. If the resource is a Manifest, the default Sequence will be used; if the resource is a Canvas, the first image Annotation
     * will be used; if the resource is an Annotation, the ContentResource will also be used.
     * @return string[]
     */
    public function getRenderingUrlsForFormat($format, $useChildResources = true) {
        $renderingUrls = [];
        if (empty($format)) {
            return $renderingUrls;
        }
        if ($this->rendering!=null && is_array($this->rendering)) {
            if (JsonLdHelper::isSimpleArray($this->rendering)) {
                foreach ($this->rendering as $rendering) {
                    if (!is_array($rendering)) {
                        continue;
                    }
                    if (array_key_exists("format", $rendering) && $rendering["format"] == $format && array_key_exists("@id", $rendering)) {
                        $renderingUrls[] = $rendering["@id"];
                    }
                }
            } else {
                if (array_key_exists("format", $this->rendering) && $this->rendering["format"] == $format && array_key_exists("@id", $this->rendering)) {
                    $renderingUrls[] = $this->rendering["@id"];
                }
            }
        }
        if (empty($renderingUrls) && $useChildResources) {
            if ($this instanceof Manifest2 && !empty($this->getSequences())) {
                $renderingUrls = $this->getSequences()[0]->getRenderingUrlsForFormat($format);
            }
            elseif ($this instanceof Canvas2 && !empty($this->getImages())) {
                $renderingUrls = $this->getImages()[0]->getRenderingUrlsForFormat($format);
            }
            elseif ($this instanceof Annotation2 && $this->getResource()!=null && $this->getResource() instanceof ContentResource2) {
                $renderingUrls = $this->getResource()->getRenderingUrlsForFormat($format);
            }
        }
        return $renderingUrls;
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
        return $this->getValueForDisplay($this->attribution, $language, $joinChars, true, $options);
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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getSummary()
     */
    public function getSummary() {
        return $this->description;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getSummaryForDisplay()
     */
    public function getSummaryForDisplay($language = null, $joinChars = "; ", $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML) {
        return $this->getValueForDisplay($this->description, $language = null, $joinChars = "; ", true, $options);
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        if ($this->thumbnail!=null) {
            $thumbnail = JsonLdHelper::isSimpleArray($this->thumbnail) ? empty ($this->thumbnail) ? null : $this->thumbnail[0] : $this->thumbnail;
            if (is_string($thumbnail)) {
                return $thumbnail;
            }
            $imageService = null;
            $width = null;
            $height = null;
            $simpleUrl = null;
            if ($thumbnail instanceof ContentResource2) {
                $imageService = $thumbnail->getService();
                $width = $thumbnail->getWidth();
                $height = $thumbnail->getHeight();
                $simpleUrl = $thumbnail->getId();
            } elseif (JsonLdHelper::isDictionary($thumbnail)) {
                if (array_key_exists("service", $thumbnail)) {
                    if (JsonLdHelper::isDictionary($thumbnail["service"])) {
                        $imageService = IiifHelper::loadIiifResource($thumbnail["service"]);
                    }
                } elseif (array_key_exists(Keywords::ID, $thumbnail)) {
                    $simpleUrl = $thumbnail[Keywords::ID];
                }
            }
            if ($imageService!=null && $imageService instanceof AbstractImageService) {
                if ($imageService->isFeatureSupported(Profile::SIZE_BY_H) && $imageService->isFeatureSupported(Profile::SIZE_BY_W)) {
                    // Level 1 or Level 2 or at least sufficient additional features in profile
                    $width = $width == null ? Options::getMaxThumbnailWidth() : $width;
                    $height = $heigth == null ? Options::getMaxThumbnailHeight() : $heigth;
                    $size = $width <= $height ? (",".$height) : ($width.",");
                    return $imageService->getImageUrl(null, $size, null, null, null);
                }
                if (!empty($imageService->getSizes()) && is_array($imageService->getSizes())) {
                    // Level 0 image with given "sizes"
                    $sizeWidth = null;
                    $sizeHeight = null;
                    foreach ($imageService->getSizes() as $sizeItem) {
                        if ($sizeWidth == null || $sizeHeight == null || ($sizeItem["width"]<Options::getMaxThumbnailWidth() && $sizeItem["height"]<Options::getMaxThumbnailHeight())) {
                            $sizeWidth = $sizeItem["width"];
                            $sizeHeight = $sizeItem["height"];
                        }
                    }
                    $size = $sizeWidth <= $sizeHeight ? (",".$sizeHeight) : ($sizeWidth.",");
                    return $imageService->getImageUrl(null, $size, null, null, null);
                }
                if ($imageService->getWidth()!= null) {
                    // Level 0 image with at least a given width
                    return $imageService->getImageUrl(null, $imageService->getWidth().",", null, null, null);
                }
                if ($imageService->getHeight()!= null) {
                    // Level 0 image with at least a given height
                    return $imageService->getImageUrl(null, ",".$imageService->getHeight(), null, null, null);
                }
            }
            if ($simpleUrl!=null) {
                return $simpleUrl;
            }
        }
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getWeblinksForDisplay()
     */
    public function getWeblinksForDisplay($language = null, $joinChars = "; ") {
        return $this->getWeblinksForDisplayCommon($this->related, $language, $joinChars, "@id");
    }
}
