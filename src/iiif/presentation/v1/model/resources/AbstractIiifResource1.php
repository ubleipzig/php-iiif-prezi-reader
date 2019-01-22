<?php
namespace iiif\presentation\v1\model\resources;

use iiif\presentation\common\model\AbstractIiifEntity;
use iiif\presentation\common\model\resources\IiifResourceInterface;
use iiif\context\JsonLdHelper;
use iiif\context\Keywords;

abstract class AbstractIiifResource1 extends AbstractIiifEntity implements IiifResourceInterface {

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
     * @var string|array|\iiif\services\Service
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
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getId()
     */
    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getLabel()
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getLabelForDisplay()
     */
    public function getLabelForDisplay($language = null, $joinChar = "; ") {
        // TODO Auto-generated method stub
        
    }

    /**
     * In contrast to later versions of the Presentation API, not all resources of the Metadata API may have metadata. 
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getMetadata()
     */
    public function getMetadata() {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getMetadataForDisplay()
     */
    public function getMetadataForDisplay($language = null, $joinChars = "; ", $options = 0) {
        // TODO Auto-generated method stub
        
    }

    /**
     * Not supported by IIIF Metadata API
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getRendering()
     */
    public function getRendering() {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getRenderingUrlsForFormat()
     */
    public function getRenderingUrlsForFormat($format, $useChildResources = true) {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getRequiredStatement()
     */
    public function getRequiredStatement() {
        return $this->attribution;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getRights()
     */
    public function getRights() {
        return $this->license;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getSeeAlso()
     */
    public function getSeeAlso() {
        return $this->seeAlso;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getSeeAlsoUrlsForFormat()
     */
    public function getSeeAlsoUrlsForFormat($format) {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getSeeAlsoUrlsForProfile()
     */
    public function getSeeAlsoUrlsForProfile($profile, $startsWith = false) {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getService()
     */
    public function getService() {
        return $this->service;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getSingleService()
     */
    public function getSingleService() {
        if (empty($this->service)) {
            return null;
        }
        if (JsonLdHelper::isSequentialArray($this->service)) {
            return $this->service[0];
        }
        return $this->service; 
    }

    /**
     * In contrast to later versions of the Presentation API, not all resources of the Metadata API may have a summary. 
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getSummary()
     */
    public function getSummary() {
        return null;
    }

    /**
     * In contrast to later versions of the Presentation API, not all resources of the Metadata API may have a summary. 
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getSummaryForDisplay()
     */
    public function getSummaryForDisplay($language = null, $joinChars = "; ") {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getThumbnailUrl()
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
            if (!JsonLdHelper::isSequentialArray($value)) {
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
    
}