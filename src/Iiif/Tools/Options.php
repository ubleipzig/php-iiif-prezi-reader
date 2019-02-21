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

namespace iiif\tools;

class Options {
    
    /**
     * @var UrlReaderInterface
     */
    protected static $urlReader;
    
    /**
     * @var int
     */
    protected static $maxThumbnailWidth = null;
    
    /**
     * @var int
     */
    protected static $maxThumbnailHeight = null;

    /**
     * @return \iiif\tools\UrlReaderInterface
     */
    public static function getUrlReader() {
        return self::$urlReader;
    }

    /**
     * @return number
     */
    public static function getMaxThumbnailWidth() {
        return self::$maxThumbnailWidth == null ? 100 : self::$maxThumbnailWidth;
    }

    /**
     * @return number
     */
    public static function getMaxThumbnailHeight() {
        return self::$maxThumbnailHeight == null ? 100 : self::$maxThumbnailHeight;
    }

    /**
     * @param \iiif\tools\UrlReaderInterface $urlReader
     */
    public static function setUrlReader($urlReader) {
        Options::$urlReader = $urlReader;
    }

    /**
     * @param number $maxThumbnailWidth
     */
    public static function setMaxThumbnailWidth($maxThumbnailWidth) {
        self::$maxThumbnailWidth = $maxThumbnailWidth;
    }

    /**
     * @param number $maxThumbnailHeight
     */
    public static function setMaxThumbnailHeight($maxThumbnailHeight) {
        self::$maxThumbnailHeight = $maxThumbnailHeight;
    }

}

