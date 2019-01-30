<?php
namespace iiif\presentation\common\vocabulary;

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

