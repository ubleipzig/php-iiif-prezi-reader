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
     * @return string|array
     */
    public function getMetadata();
    
    /**
     * version 2: description
     * version 3: summary
     * @return string|array  
     */
    public function getSummary();
    
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
    
    public function getSeeAlsoUrlsForFormat(string $format);
    
    public function getSeeAlsoUrlsForProfile(string $profile, bool $startsWith = false);
        
    /**
     * @return Service
     */
    public function getService();
    
    public function getRendering();
    
    public function getRenderingUrlsForFormat(string $format, bool $useChildResources = true);
    
    /**
     * @return string
     */
    public function getThumbnailUrl();

    public function getDefaultLabel();
}

