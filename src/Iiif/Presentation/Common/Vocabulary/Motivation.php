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

namespace Ubl\Iiif\Presentation\Common\Vocabulary;

class Motivation {

    /**
     * @deprecated
     */
    const OA_ASSESSING = "oa:assessing";
    
    /**
     * @deprecated
     */
    const OA_BOOKMARKING = "oa:bookmarking";
    
    /**
     * @deprecated
     */
    const OA_CLASSIFYING = "oa:classifying";
    
    /**
     * @deprecated
     */
    const OA_COMMENTING = "oa:commenting";
    
    /**
     * @deprecated
     */
    const OA_DESCRIBING = "oa:describing";
    
    /**
     * @deprecated
     */
    const OA_EDITING = "oa:editing";
    
    /**
     * @deprecated
     */
    const OA_HIGHLIGHTING = "oa:highlighting";
    
    /**
     * @deprecated
     */
    const OA_IDENTIFYING = "oa:identifying";
    
    /**
     * @deprecated
     */
    const OA_LINKING = "oa:linking";
    
    /**
     * @deprecated
     */
    const OA_MODERATING = "oa:moderating";
    
    /**
     * @deprecated
     */
    const OA_QUESTIONING = "oa:questioning";
    
    /**
     * @deprecated
     */
    const OA_REPLYING = "oa:replying";
    
    /**
     * @deprecated
     */
    const OA_TAGGING = "oa:tagging";
    
    /**
     * @deprecated
     */
    const SHARED_CANVAS_PAINTING_IRI = "http://www.shared-canvas.org/ns/painting";

    /**
     * @deprecated
     */
    const IIIF_PRESENTATION2_PAINTING_IRI = "http://iiif.io/api/presentation/2#painting";

    /**
     * @deprecated
     */
    const IIIF_PRESENTATION3_PAINTING_IRI = "http://iiif.io/api/presentation/3#painting";
    
    /**
     * Painting motivation as it appears in Metadata API manifests
     * @var string
     * @deprecated
     */
    const SHARED_CANVAS_PAINTING = "sc:painting";

    /**
     * Painting motivation as it appears in Presentation API 2 manifests
     * @var string
     * @deprecated
     */
    const IIIF_PRESENTATION2_PAINTING = "sc:painting";

    /**
     * Painting motivation as it appears in Presentation API 3 manifests
     * @var string
     * @deprecated
     */
    const IIIF_PRESENTATION3_PAINTING = "painting";

    /**
     * @deprecated
     * @var array
     */
    const VALID_PAINTING_MOTIVATIONS = [
        self::SHARED_CANVAS_PAINTING_IRI,
        self::IIIF_PRESENTATION2_PAINTING_IRI,
        self::IIIF_PRESENTATION3_PAINTING_IRI,
        self::SHARED_CANVAS_PAINTING,
        self::IIIF_PRESENTATION2_PAINTING,
        self::IIIF_PRESENTATION3_PAINTING
    ];

    const PAINTING = [
        "http://www.shared-canvas.org/ns/painting",
        "http://iiif.io/api/presentation/2#painting",
        "http://iiif.io/api/presentation/3#painting",
        "iiif_prezi:painting",
        "sc:painting",
        "painting"
    ];
    
    public static function isPaintingMotivation($motivation) {
        return $motivation != null && (array_search($motivation, self::PAINTING) != false);
    }

    
}

