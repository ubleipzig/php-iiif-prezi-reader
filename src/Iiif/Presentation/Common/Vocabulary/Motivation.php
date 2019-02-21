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

    const OA_ASSESSING = "oa:assessing";
    
    const OA_BOOKMARKING = "oa:bookmarking";
    
    const OA_CLASSIFYING = "oa:classifying";
    
    const OA_COMMENTING = "oa:commenting";
    
    const OA_DESCRIBING = "oa:describing";
    
    const OA_EDITING = "oa:editing";
    
    const OA_HIGHLIGHTING = "oa:highlighting";
    
    const OA_IDENTIFYING = "oa:identifying";
    
    const OA_LINKING = "oa:linking";
    
    const OA_MODERATING = "oa:moderating";
    
    const OA_QUESTIONING = "oa:questioning";
    
    const OA_REPLYING = "oa:replying";
    
    const OA_TAGGING = "oa:tagging";
    
    const SHARED_CANVAS_PAINTING_IRI = "http://www.shared-canvas.org/ns/painting";

    const IIIF_PRESENTATION2_PAINTING_IRI = "http://iiif.io/api/presentation/2#painting";

    const IIIF_PRESENTATION3_PAINTING_IRI = "http://iiif.io/api/presentation/3#painting";
    
    /**
     * Painting motivation as it appears in Metadata API manifests
     * @var string
     */
    const SHARED_CANVAS_PAINTING = "sc:painting";

    /**
     * Painting motivation as it appears in Presentation API 2 manifests
     * @var string
     */
    const IIIF_PRESENTATION2_PAINTING = "sc:painting";

    /**
     * Painting motivation as it appears in Presentation API 3 manifests
     * @var string
     */
    const IIIF_PRESENTATION3_PAINTING = "painting";

    const VALID_PAINTING_MOTIVATIONS = [
        self::SHARED_CANVAS_PAINTING_IRI,
        self::IIIF_PRESENTATION2_PAINTING_IRI,
        self::IIIF_PRESENTATION3_PAINTING_IRI,
        self::SHARED_CANVAS_PAINTING,
        self::IIIF_PRESENTATION2_PAINTING,
        self::IIIF_PRESENTATION3_PAINTING
    ];

    public static function isPaintingMotivation($motivation) {
        return $motivation != null && (array_search($motivation, self::VALID_PAINTING_MOTIVATIONS) != false);
    }

}

