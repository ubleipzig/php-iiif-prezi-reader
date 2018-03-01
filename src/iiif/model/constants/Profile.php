<?php
namespace iiif\model\constants;

class Profile
{
    const IIIF2_LEVEL0 = "http://iiif.io/api/image/2/level0.json";
    const IIIF2_LEVEL1 = "http://iiif.io/api/image/2/level1.json";
    const IIIF2_LEVEL2 = "http://iiif.io/api/image/2/level2.json";
    
    const BASE_URI_REDIRECT = "baseUriRedirect";
    const CORS = "cors";
    const JSONLD_MEDIA_TYPE = "jsonldMediaType";
    const PROFILE_LINK_HEADER = "profileLinkHeader";
    const REGION_BY_PCT = "regionByPct";
    const REGION_BY_PX = "regionByPx";
    const ROTATION_BY_90S = "rotationBy90s";
    const SIZE_BY_WH_LISTED = "sizeByWhListed";
    const SIZE_BY_CONFINED_WH = "sizeByConfinedWh";
    const SIZE_BY_DISTORTED_WH = "sizeByDistortedWh";
    const SIZE_BY_FORCED_WH = "sizeByForcedWh";
    const SIZE_BY_H = "sizeByH";
    const SIZE_BY_PCT = "sizeByPct";
    const SIZE_BY_W = "sizeByW";
    const SIZE_BY_WH = "sizeByWh";
    
    const SUPPORTED_BY_LEVEL=array(
        self::IIIF2_LEVEL0=>[self::SIZE_BY_WH_LISTED],
        self::IIIF2_LEVEL1=>[self::SIZE_BY_WH_LISTED, self::BASE_URI_REDIRECT, self::CORS, self::JSONLD_MEDIA_TYPE, self::REGION_BY_PX, self::SIZE_BY_H,
            self::SIZE_BY_PCT, self::SIZE_BY_W],
        self::IIIF2_LEVEL2=>[self::BASE_URI_REDIRECT, self::CORS, self::JSONLD_MEDIA_TYPE, self::PROFILE_LINK_HEADER, self::REGION_BY_PCT,
            self::REGION_BY_PX, self::ROTATION_BY_90S, self::SIZE_BY_WH_LISTED, self::SIZE_BY_CONFINED_WH, self::SIZE_BY_DISTORTED_WH, self::SIZE_BY_FORCED_WH,
            self::SIZE_BY_H, self::SIZE_BY_PCT, self::SIZE_BY_W, self::SIZE_BY_WH
        ]
    );
}

