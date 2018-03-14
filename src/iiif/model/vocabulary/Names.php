<?php
namespace iiif\model\vocabulary;

class Names
{
    // Structures
    CONST COLLECTIONS="collections";
    CONST MANIFESTS="manifests";
    CONST MEMBERS="members";
    CONST SEQUENCES="sequences";
    CONST STRUCTURES="structures";
    CONST CANVASES="canvases";
    CONST RESOURCES="resources";
    CONST OTHER_CONTENT="otherContent";
    CONST IMAGES="images";
    CONST RANGES="ranges";
    
    // Technical
    CONST CONTEXT="@context";
    CONST ID="@id";
    CONST TYPE="@type";
    
    CONST FORMAT="format";
    CONST HEIGHT="height";
    CONST WIDTH="width";
    CONST VIEWING_DIRECTION="viewingDirection";
    CONST VIEWING_HINT="viewingHint";
    CONST NAV_DATE="navDate";
    
    // Descriptive, rights
    CONST LABEL="label";
    CONST DESCRIPTION="description";
    CONST METADATA="metadata";
    CONST THUMBNAIL="thumbnail";
    CONST ATTRIBUTION="attribution";
    CONST LICENSE="license";
    CONST LOGO="logo";
    
    // Linking
    CONST SEE_ALSO="seeAlso";
    CONST SERVICE="service";
    CONST RELATED="related";
    CONST RENDERING="rendering";
    CONST WITHIN="within";
    CONST START_CANVAS="startCanvas";
    
    // Resource specific properties
    CONST MOTIVATION="motivation";
    CONST RESOURCE="resource";
    CONST ON="on";
    CONST PROFILE="profile";

    
    // IIIF Image Profile
    const SUPPORTS = "supports";

    // Value for label/value metadata
    const VALUE = "value";
    
    // Language Properties
    const AT_LANGUAGE = "@language";
    const AT_VALUE = "@value";
}

