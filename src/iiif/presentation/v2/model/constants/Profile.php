<?php
namespace iiif\presentation\v2\model\constants;

class Profile
{
    const IIIF2_LEVEL0 = "http://iiif.io/api/image/2/level0.json";
    const IIIF2_LEVEL1 = "http://iiif.io/api/image/2/level1.json";
    const IIIF2_LEVEL2 = "http://iiif.io/api/image/2/level2.json";
    
    const IIIF3_LEVEL0 = "level0";
    const IIIF3_LEVEL1 = "level1";
    const IIIF3_LEVEL2 = "level2";

    // TODO compliance level json docs are not yet available via URL
    const IIIF3_LEVEL0_URL = "http://iiif.io/api/image/3/level0.json";
    const IIIF3_LEVEL1_URL = "http://iiif.io/api/image/3/level1.json";
    const IIIF3_LEVEL2_URL = "http://iiif.io/api/image/3/level2.json";
    
    const BASE_URI_REDIRECT = "baseUriRedirect";
    const CANONICAL_LINK_HEADER = "canonicalLinkHeader";
    const CORS = "cors";
    const JSONLD_MEDIA_TYPE = "jsonldMediaType";
    const MIRRORING = "mirroring";
    const PROFILE_LINK_HEADER = "profileLinkHeader";
    const REGION_BY_PCT = "regionByPct";
    const REGION_BY_PX = "regionByPx";
    const REGION_SQUARE = "regionSquare";
    const ROTATION_ARBITRARY = "rotationArbitrary";
    const ROTATION_BY_90S = "rotationBy90s";
    const SIZE_ABOVE_FULL = "sizeAboveFull";
    const SIZE_BY_CONFINED_WH = "sizeByConfinedWh";
    const SIZE_BY_DISTORTED_WH = "sizeByDistortedWh";
    const SIZE_BY_FORCED_WH = "sizeByForcedWh";
    const SIZE_BY_H = "sizeByH";
    const SIZE_BY_PCT = "sizeByPct";
    const SIZE_BY_W = "sizeByW";
    const SIZE_BY_WH = "sizeByWh";
    const SIZE_BY_WH_LISTED = "sizeByWhListed";
    
    const SUPPORTED_BY_LEVEL=array(
        self::IIIF2_LEVEL0 => [self::SIZE_BY_WH_LISTED],
        self::IIIF2_LEVEL1 => [self::SIZE_BY_WH_LISTED, self::BASE_URI_REDIRECT, self::CORS, self::JSONLD_MEDIA_TYPE, self::REGION_BY_PX, self::SIZE_BY_H,
            self::SIZE_BY_PCT, self::SIZE_BY_W],
        self::IIIF2_LEVEL2 => [self::BASE_URI_REDIRECT, self::CORS, self::JSONLD_MEDIA_TYPE, self::PROFILE_LINK_HEADER, self::REGION_BY_PCT,
            self::REGION_BY_PX, self::ROTATION_BY_90S, self::SIZE_BY_WH_LISTED, self::SIZE_BY_CONFINED_WH, self::SIZE_BY_DISTORTED_WH, self::SIZE_BY_FORCED_WH,
            self::SIZE_BY_H, self::SIZE_BY_PCT, self::SIZE_BY_W, self::SIZE_BY_WH
        ],
        self::IIIF3_LEVEL0 => [],
        self::IIIF3_LEVEL1 => [self::BASE_URI_REDIRECT, self::CORS, self::JSONLD_MEDIA_TYPE, self::REGION_BY_PX, self::REGION_SQUARE, self::SIZE_BY_H, self::SIZE_BY_W],
        self::IIIF3_LEVEL2 => [self::BASE_URI_REDIRECT, self::CORS, self::JSONLD_MEDIA_TYPE, self::REGION_BY_PCT, self::REGION_BY_PX, self::REGION_SQUARE, self::ROTATION_BY_90S,
            self::SIZE_BY_H, self::SIZE_BY_PCT, self::SIZE_BY_CONFINED_WH, self::SIZE_BY_W, self::SIZE_BY_WH]
    );
}

