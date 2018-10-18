<?php
namespace iiif\presentation\v2\model\resources;

use iiif\context\IRI;
use iiif\context\JsonLdContext;
use iiif\context\JsonLdProcessor;
use iiif\context\Keywords;
use iiif\presentation\common\TypeMap;
use iiif\presentation\common\model\AbstractIiifEntity;
use iiif\presentation\v2\model\vocabulary\Names;
use iiif\services\Service;

/**
 * Bundles all resource properties that every single iiif resource type may have
 * see http://iiif.io/api/presentation/2.1/#resource-properties
 *
 * @author lutzhelm
 *        
 */
abstract class AbstractIiifResource extends AbstractIiifEntity {

    // http://iiif.io/api/presentation/2.1/#technical-properties
    /**
     *
     * @var string
     */
    protected $id;

    protected $viewingHint;

    // http://iiif.io/api/presentation/2.1/#descriptive-properties
    protected $label;

    protected $metadata;

    protected $description;

    /**
     *
     * @var ContentResource
     */
    protected $thumbnail;

    // http://iiif.io/api/presentation/2.1/#rights-and-licensing-properties
    protected $attribution;

    protected $license;

    protected $logo;

    // http://iiif.io/api/presentation/2.1/#linking-properties
    protected $related;

    protected $rendering;

    /**
     *
     * @var Service
     */
    protected $service;

    protected $seeAlso;

    protected $within;

    protected $preferredLanguage;

    // keep it for faster and easier searches
    protected $originalJsonArray;

    protected $originalJson;

    /**
     *
     * @var boolean
     */
    protected $reference;

    public static function loadIiifResource($resource) {
        if (is_string($resource) && IRI::isAbsoluteIri($resource)) {
            $resource = file_get_contents($resource);
        }
        if (is_string($resource)) {
            $resource = json_decode($resource, true);
        }
        if (JsonLdProcessor::isDictionary($resource)) {
            return self::parseDictionary($resource);
        }
        return null;
    }

    // public static function fromJson($jsonAsString)
    // {
    // $jsonAsArray = json_decode($jsonAsString, true);
    // $resource = static::fromArray($jsonAsArray);
    // $resource->originalJson = $jsonAsString;
    // return $resource;
    // }
    abstract public static function fromArray($jsonAsArray, &$allResources = array());

    private static function getResourceIdWithoutFragment($original, $resourceClass = null) {
        $resourceClass = $resourceClass == null ? get_called_class() : $resourceClass;
        if ($original != null && $resourceClass == Canvas::class && strpos($original, '#') !== false) {
            // FIXME xywh URL fragments are currently lost
            return explode('#', $original)[0];
        }
        return $original;
    }

    protected function getTypelessProperties() {
        return [
            "service"
        ];
    }

    /**
     *
     * {@inheritdoc}
     * @see \iiif\presentation\common\model\AbstractIiifEntity::getValueForSpecialProperty()
     */
    protected function getValueForTypelessProperty($property, $dictionary, JsonLdContext $context) {
        if ($property = "service") {
            if ($this instanceof ContentResource && $this->getType() == "http://dublincore.org/documents/dcmi-type-vocabulary/#dcmitype-Image") {
                $contextOrAlias = $context->getKeywordOrAlias(Keywords::CONTEXT);
                $idOrAlias = $context->getKeywordOrAlias(Keywords::ID);
                if (array_key_exists($contextOrAlias, $dictionary) && array_key_exists($dictionary[$contextOrAlias], TypeMap::SERVICE_TYPES)) {
                    $clazz = TypeMap::SERVICE_TYPES[$dictionary[$contextOrAlias]];
                }
            }
            $service = $clazz == null ? new Service() : new $clazz();
            $service->id = array_key_exists($idOrAlias, $dictionary) ? $dictionary[$idOrAlias] : null;
            // TODO use a profile entity
            $service->profile = array_key_exists("profile", $dictionary) ? $dictionary["profile"] : null;
            return $service;
        }
    }

    protected function getTranslatedField($field, $language) {
        if (is_null($field))
            return null;
        if (is_string($field))
            return $field;
        if (is_array($field)) {
            if (is_array($field[0])) {
                $selectedValue = $field[0];
                if (! (is_null($language))) {
                    foreach ($field as $valueAndLanguage) {
                        if ($valueAndLanguage[Names::AT_LANGUAGE] == $language) {
                            $selectedValue = $valueAndLanguage;
                        }
                    }
                }
                return is_null($selectedValue) ? null : $selectedValue["@value"];
            }
            return $field;
        }
        return null;
    }

    protected function getPreferredTranslation($field) {
        return $this->getTranslatedField($field, $this->preferredLanguage);
    }

    public function getDefaultLabel() {
        return $this->getPreferredTranslation($this->label);
    }

    // /**
    // *
    // * @param array $jsonAsArray
    // * @param array $allResources
    // * @return AbstractIiifResource
    // */
    // protected static function createInstanceFromArray($jsonAsArray, &$allResources)
    // {
    // // everything but sequences and annotations MUST have an id, annotations still should have an id
    // $resourceId=self::getResourceIdWithoutFragment(array_key_exists(Names::ID, $jsonAsArray) ? $jsonAsArray[Names::ID] : null);
    // $instance = null;
    // if ($resourceId != null && array_key_exists($resourceId, $allResources) && $allResources[$resourceId] != null) {
    // // TODO Is there any way that there is more than a reference in the $allResources array?
    // $instance = &$allResources[$resourceId];
    // } else {
    // $clazz = get_called_class();
    // $instance = new $clazz();
    // $allResources[$resourceId] = &$instance;
    // }
    // $instance->id = $resourceId;
    // return $instance;
    // }
    
    // protected function loadPropertiesFromArray($jsonAsArray, &$allResources)
    // {
    // $this->originalJsonArray = $jsonAsArray;
    // $this->type = array_key_exists(Names::TYPE, $jsonAsArray) ? $jsonAsArray[Names::TYPE] : null;
    // $this->label = array_key_exists(Names::LABEL, $jsonAsArray) ? $jsonAsArray[Names::LABEL] : null;
    // $this->metadata = array_key_exists(Names::METADATA, $jsonAsArray) ? $jsonAsArray[Names::METADATA] : null;
    
    // $this->service = array_key_exists(Names::SERVICE, $jsonAsArray) ? Service::fromArray($jsonAsArray[Names::SERVICE]) : null;
    // // TODO According to the specs, some of the resources may provide more than one thumbnail per resource. Value for "thumbnail" can be json array and json object
    // $this->thumbnail = array_key_exists(Names::THUMBNAIL, $jsonAsArray) && isset($jsonAsArray[Names::THUMBNAIL]) ? Thumbnail::fromArray($jsonAsArray[Names::THUMBNAIL]) : null;
    // $this->viewingHint = array_key_exists(Names::VIEWING_HINT, $jsonAsArray) && isset($jsonAsArray[Names::VIEWING_HINT]) ? $jsonAsArray[Names::VIEWING_HINT] : null;
    // // TODO all the other properties
    // }
    
    // // TODO check if one unified method for resource loading is possible
    // // FIXME make this static and return value
    // protected function loadResources($jsonAsArray, $resourceFieldName, $resourceClass, &$targetArray, &$allResources)
    // {
    // if (!is_array($jsonAsArray))
    // {
    // throw new \Exception("$jsonAsArray ".$jsonAsArray." is not an array.");
    // }
    // if (array_key_exists($resourceFieldName, $jsonAsArray))
    // {
    // $resourcesAsArray = $jsonAsArray[$resourceFieldName];
    
    // if (isset($resourcesAsArray)) {
    // foreach ($resourcesAsArray as $resourceAsArray)
    // {
    // $resource = null;
    // if (is_array($resourceAsArray)) {
    // $resource = $resourceClass::fromArray($resourceAsArray, $allResources);
    // }
    // elseif (is_string($resourceAsArray)) {
    // $resourceId = self::getResourceIdWithoutFragment($resourceAsArray, $resourceClass);
    // if (array_key_exists($resourceId, $allResources) && $allResources[$resourceId] != null) {
    // $resource = $allResources[$resourceId];
    // }
    // else {
    // $resource = new $resourceClass();
    // $resource->reference = true;
    // $resource->id = $resourceId;
    
    // $allResources[$resourceId] = $resource;
    // }
    // }
    // if ($resource != null) $targetArray[] = $resource;
    // }
    // }
    // }
    // }
    
    // // FIXME make this static and return value
    // protected function loadSingleResouce($jsonAsArray, $resourceFieldName, $resourceClass, &$targetField, &$allResources)
    // {
    // if (!is_array($jsonAsArray))
    // {
    // throw new \Exception("$jsonAsArray ".$jsonArray." is not an array.");
    // }
    // if (array_key_exists($resourceFieldName, $jsonAsArray))
    // {
    // $resourceAsArray = $jsonAsArray[$resourceFieldName];
    // $resource = $resourceClass::fromArray($resourceAsArray, $allResources);
    // $targetField = $resource;
    // }
    // }
    
    // // FIXME make this static and return value
    // protected function loadMixedResources($jsonAsArray, $resourceFieldName, $configArray, &$targetArray, &$allResources)
    // {
    // if (!is_array($jsonAsArray))
    // {
    // throw new \Exception("$jsonAsArray ".$jsonArray." is not an array.");
    // }
    // if (array_key_exists($resourceFieldName, $jsonAsArray))
    // {
    // $resourcesAsArray = $jsonAsArray[$resourceFieldName];
    // if (isset($resourcesAsArray)) {
    // foreach ($resourcesAsArray as $resourceAsArray)
    // {
    // foreach ($configArray as $config)
    // {
    // if ($resourceAsArray[Names::TYPE]==$config[Names::TYPE])
    // {
    // $resourceClass = $config[MiscNames::CLAZZ];
    // $resource = $resourceClass::fromArray($resourceAsArray, $allResources);
    // $targetArray[] = $resource;
    // break;
    // }
    // }
    // }
    // }
    // }
    // }
    protected function getContainedResources() {}

    /**
     *
     * @return boolean
     */
    public function isReference() {
        return $this->reference;
    }

    /**
     *
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     * @return \iiif\presentation\v2\model\resources\Service
     */
    public function getService() {
        return $this->service;
    }

    /**
     *
     * @return \iiif\presentation\v2\model\resources\ContentResource
     */
    public function getThumbnail() {
        return $this->thumbnail;
    }

    public function getThumbnailImageUrl($width = null, $height = null) {
        // TODO
    }

    /**
     *
     * @return mixed
     */
    public function getViewingHint() {
        return $this->viewingHint;
    }

    /**
     *
     * @return mixed
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     *
     * @return mixed
     */
    public function getMetadata() {
        return $this->metadata;
    }

    /**
     *
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     *
     * @return mixed
     */
    public function getOriginalJsonArray() {
        return $this->originalJsonArray;
    }

    public function getTranslatedLabel($language = null) {
        return self::getTranslatedField($this->label, $language);
    }

    /**
     *
     * @param string $label
     * @param string $language
     * @return string value for given metadata label; preferably in the same language as the label if
     *         no language is given; first language language is provided by neither the $language paramater
     *         nor the label.
     */
    public function getMetadataForLabel($label, $language = null) {
        /*
         * ALL teh possibilities!
         * [{"label": {"@value": "Example label", "@language": "en"}, "value": {"@value": "Example value", "@language": "en"}}]
         * [{"label": "Example label", "value": "Example value"}]
         * ... and combinations with language/translation info either in the label or in the value.
         *
         * TODO Presentation API 3 will change the structure: http://prezi3.iiif.io/api/presentation/3.0/#language-of-property-values
         */
        if ($this->metadata == null || $label == null)
            return null;
        $requestedValue = null;
        $targetLanguage = $language == null ? null : $language;
        foreach ($this->metadata as $metadatum) {
            if (is_string($metadatum[Names::LABEL])) {
                if ($label == $metadatum[Names::LABEL]) {
                    $requestedValue = $metadatum[Names::VALUE];
                    break;
                }
            } elseif (is_array($metadatum[Names::LABEL])) {
                foreach ($metadatum[Names::LABEL] as $translatedLabel) {
                    if ($label == $translatedLabel[Names::AT_VALUE]) {
                        $requestedValue = $requestedValue == null ? $metadatum[Names::VALUE] : $requestedValue;
                        if ($targetLanguage == null) {
                            $targetLanguage = $translatedLabel[Names::AT_LANGUAGE];
                        }
                        if ($language != null && $translatedLabel[Names::AT_LANGUAGE] == $language) {
                            $requestedValue = $metadatum[Names::VALUE];
                            break 2;
                        }
                    }
                }
            }
        }
        if ($requestedValue == null) {
            return null;
        }
        if (is_string($requestedValue)) {
            return $requestedValue;
        }
        if (is_array($requestedValue)) {
            if (is_array($requestedValue[0])) {
                $firstValue = null;
                foreach ($requestedValue as $translatedValue) {
                    if ($translatedValue[Names::AT_LANGUAGE] == $targetLanguage) {
                        return $translatedValue[Names::AT_VALUE];
                    }
                    if ($firstValue == null) {
                        $firstValue = $translatedValue[Names::AT_VALUE];
                    }
                }
                return $firstValue;
            }
            return $requestedValue;
        }
        // this shouldn't happen
        return null;
    }
}
