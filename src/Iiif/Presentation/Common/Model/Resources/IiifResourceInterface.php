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

namespace Ubl\Iiif\Presentation\Common\Model\Resources;

interface IiifResourceInterface {

    /**
     * Flag constant for metadata value sanitization: remove all (HTML) tags
     * @var integer
     */
    const SANITIZE_NO_TAGS = 1;
    
    /**
     * Flag constant for metadata value sanitization: encode the whole 
     * @var integer
     */
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
     * @param string $language RFC 5646 language code, see https://tools.ietf.org/html/rfc5646 . If none
     * is given, "@none" / no code will be used. See language of properties:
     * version 2: https://iiif.io/api/presentation/2.1/#language-of-property-values
     * version 3: https://iiif.io/api/presentation/3.0/#44-language-of-property-values
     * @param string $joinChars Used to join multi value labels. If set to null, array will be returned.
     * @return string|string[]
     */
    public function getLabelForDisplay($language = null, $joinChars = "; ");
    
    /**
     * @return string|array
     */
    public function getMetadata();
    
    /**
     * 
     * @param string $language RFC 5646 language code, see https://tools.ietf.org/html/rfc5646 . If none
     * is given, "@none" / no code will be used. See language of properties:
     * version 2: https://iiif.io/api/presentation/2.1/#language-of-property-values
     * version 3: https://iiif.io/api/presentation/3.0/#44-language-of-property-values
     * @param string $joinChars Used to join multiple values. If set to null, an array will be returned
     * as value for each metadata label.
     * @param int $options sanitazition options for HTML content. One of SANITIZE_NO_TAGS,
     * SANITIZE_XML_ENCODE_ALL and SANITIZE_XML_ENCODE_NONHTML.
     */
    public function getMetadataForDisplay($language = null, $joinChars = "; ", $options = 0);
    
    /**
     * Get a normalized and translated form of the "related" (v1, v2) or "homepage" (v3) field.
     * @param string $language
     * @param string $joinChars If multiple labels of the same language are 
     * present for any related resource, join them with $joinChars; set label
     * to array if $joinchars is null.
     * @return array A list of associative arrays containing the URL as "@id" and optionally a translated "label" as well as a "format".
     */
    public function getWeblinksForDisplay($language = null, $joinChars = "; ");
    
    /**
     * version 1: not applicable
     * version 2: "description", see https://iiif.io/api/presentation/2.1/#description
     * version 3: "summary", see https://iiif.io/api/presentation/3.0/#summary
     * @return string|array  Description / summary as it is contained in the manifest. This might be an array or just a string.
     */
    public function getSummary();

    /**
     * 
     * @param string $language
     * @param string $joinChars
     * @param int $options
     * @return string|array
     */
    public function getSummaryForDisplay($language = null, $joinChars = "; ", $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML);
    
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
    
    public function getRequiredStatementForDisplay($language = null, $joinChars = "; ", $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML);
    
    public function getSeeAlso();
    /**
     * Looks a matching "format" value in all entries of seeAlso and returns their URLs
     * @param string $format requested format
     * @return string[] Array of URLs / @id s of all matching entries 
     */
    public function getSeeAlsoUrlsForFormat($format);

    /**
     * Looks for matching "profile" value in all entries of "seeAlso" and return their URLs
     * @param string $profile Requested profile
     * @param boolean $startsWith If true, a seeAlso entry matches if it's profile
     * starts with $profile. For example, "http://example.org/service/version1" will
     * match "http://example.org/service/" if $startsWith is true, but will not match if it's false.
     * @return Array of URLs / @id s of all matching entries
     */
    public function getSeeAlsoUrlsForProfile($profile, $startsWith = false);
        
    /**
     * @return \Ubl\Iiif\Services\Service|\Ubl\Iiif\Services\Service[]
     */
    public function getService();
    
    /**
     * @return \Iterator Return content of "service" as Iterator of Service objects
     */
    public function getServiceIterator();
    
    /**
     * @return \Ubl\Iiif\Services\Service The first service, either linked or embedded.
     */
    public function getSingleService();
    
    /**
     * @return \Ubl\Iiif\Services\Service The first service, either lazy loaded if linked, or embedded.
     */
    public function getLazyLoadedSingleService();
    
    /**
     * @return string|array Any number of URLs representing a rendered version of the current resource, e.g. a PDF, HTML doc etc,
     * as string, array of strings, associated array of @id an label where the @id contains the URL, or an array with a list of the latter.  
     */
    public function getRendering();
    
    public function getRenderingUrlsForFormat($format, $useChildResources = true);
    
    /**
     * @return string A thumbnail URL for the resource, either provided by a "thumbnail" property or
     * generated with default dimensions in @link \Ubl\Iiif\Tools\Options.
     */
    public function getThumbnailUrl();

    /**
     * Extract data from json node by JsonPath expression
     * 
     * @param string $expression
     * 
     * @link https://goessner.net/articles/JsonPath/
     */
    public function jsonPath($expression);
    
    /**
     * @return boolean This IIIF resource is only linked, not embedded, and can be loaded lazily
     */
    public function isLinkedResource();

}

