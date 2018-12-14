<?php
namespace iiif\presentation\common\model\resources;

use iiif\services\Service;

interface IiifResourceInterface {
    
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
     * @param string $joinChar Used to join multi value labels. If set to null, array will be return.
     * @param bool $switchToExistingLanguage Use the first existing language if requested language is not present.
     * @return string|string[]
     */
    public function getLabelForDisplay($language = null, $joinChar = "; ");
    
    /**
     * @return string|array
     */
    public function getMetadata();
    
    public function getMetadataForDisplay($language = null, $joinChars = "; ");
    
//     public function getMetadataLabelForDisplay($label, $language = null, $joinChars = "; ", $switchToExistingLanguage = true);
    
//     public function getMetadataValueByLabelForDisplay($label, $language = null, $joinChars = "; ", $switchToExistingLanguage = true);
    
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
    
    public function getRendering();
    
    public function getRenderingUrlsForFormat($format, $useChildResources = true);
    
    /**
     * @return string A thumbnail URL for the resource.
     */
    public function getThumbnailUrl();
}

