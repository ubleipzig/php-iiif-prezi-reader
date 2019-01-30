<?php
namespace iiif\presentation\common\model\resources;

use iiif\services\Service;

interface IiifResourceInterface {

    const SANITIZE_NO_TAGS = 1;
    
    const SANITIZE_XML_ENCODE_ALL = 2;
    
    const SANITIZE_XML_ENCODE_NONHTML = 4;
    
    /**
     * @return string
     */
    public function getId();
    
    /**
     * @return string
     */
    public function getType();
    
    /**
     * @return string|array
     */
    public function getLabel();
    
    /**
     * 
     * @param string $language Language code. If none is given, "@none" will be used. 
     * @param string $joinChar Used to join multi value labels. If set to null, array will be returned.
     * @return string|string[]
     */
    public function getLabelForDisplay($language = null, $joinChar = "; ");
    
    /**
     * @return string|array
     */
    public function getMetadata();
    
    public function getMetadataForDisplay($language = null, $joinChars = "; ", $options = 0);
    
    /**
     * Get a normalized and translated form of the related (v1, v2) or homepage (v3) field.
     * @param string $language
     * @param string $joinChars If multiple labels of the same language are 
     * present for any related resource, join them with $joinChars; set label
     * to array if $joinchars is null.
     * @return array A list of assiociative arrays containg the URL as "@id" and optionally a translated "label" as well as a "format".
     */
    public function getWeblinksForDisplay($language = null, $joinChars = "; ");
    
    /**
     * version 2: description
     * version 3: summary
     * @return string|array  
     */
    public function getSummary();

    public function getSummaryForDisplay($language = null, $joinChars = "; ");
    
    /**
     * version 2: license
     * version 3: rights
     * @return string|array
     */
    public function getRights();
    
    /**
     * version 2: attribution
     * version 3: requiredStatement
     * @return string|array
     */
    public function getRequiredStatement();
    
    public function getRequiredStatementForDisplay();
    
    public function getSeeAlso();
    
    public function getSeeAlsoUrlsForFormat($format);
    
    public function getSeeAlsoUrlsForProfile($profile, $startsWith = false);
        
    /**
     * @return Service|Service[]
     */
    public function getService();
    
    /**
     * @return Service The first service.
     */
    public function getSingleService();
    
    /**
     * @return string|array Any number of URLs representing a rendered version of the current resource, e.g. a PDF, HTML doc etc,
     * as string, array of strings, assotiated array of @id an label where the @id contains the URL, or an array with a list of the latter.  
     */
    public function getRendering();
    
    public function getRenderingUrlsForFormat($format, $useChildResources = true);
    
    /**
     * @return string A thumbnail URL for the resource.
     */
    public function getThumbnailUrl();
}

