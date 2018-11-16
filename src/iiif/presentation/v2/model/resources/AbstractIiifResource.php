<?php
namespace iiif\presentation\v2\model\resources;

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
    
    protected function getCollectionProperties() {
        return [
            "profile",
            "rendering",
            "seeAlso"
        ];
    }

    /**
     *
     * {@inheritdoc}
     * @see \iiif\presentation\common\model\AbstractIiifEntity::getValueForSpecialProperty()
     */
    protected function getValueForTypelessProperty(string $property, array $dictionary, JsonLdContext $context) {
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
     * @return \iiif\services\Service
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

    /**
     * @return mixed
     */
    public function getSeeAlso() {
        return $this->seeAlso;
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
         * Presentation API 3 will fortunately change the structure: http://prezi3.iiif.io/api/presentation/3.0/#language-of-property-values
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

    public function getSeeAlsoUrlsForFormat(string $format) {
        if (!is_array($this->seeAlso)) {
            return null;
        }
        $result = [];
        $seeAlso = JsonLdProcessor::isSequentialArray($this->seeAlso) ? $this->seeAlso : [$this->seeAlso];
        foreach ($seeAlso as $candidate) {
            if (array_key_exists("format", $candidate)) {
                if ($format == $candidate["format"]) {
                    $result[] = $candidate["@id"];
                }
            }
        }
        return $result;
    }
    
    public function getSeeAlsoUrlsForProfile(string $profile, bool $startsWith = false) {
        if (!is_array($this->seeAlso)) {
            return null;
        }
        $seeAlso = JsonLdProcessor::isSequentialArray($this->seeAlso) ? $this->seeAlso : [$this->seeAlso];
        $result = [];
        foreach ($seeAlso as $candidate) {
            if (array_key_exists("profile", $candidate)) {
                if (is_string($candidate["profile"])) {
                    if ($candidate["profile"] == $profile || ($startsWith && strpos($candidate["profile"], $profile)===0)) {
                        $result[] = $candidate["@id"];
                    }
                } elseif (JsonLdProcessor::isSequentialArray($candidate["profile"])) {
                    foreach ($candidate["profile"] as $profileItem) {
                        if (is_string($profileItem) && ($profileItem == $profile || ($startsWith && strpos($profileItem, $profile)===0))) {
                            $result[] = $candidate["@id"];
                            break;
                        }
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * Get the URL of any resource linked in the "rendering" property for a given format. 
     * @param string $format format as media type (i.e. MIME type)
     * @param bool $useChildResources If set to true and the resource has not rendering URL for the requested format, the method will also look
     * for suitable rendering URLs in child resources where it might be reasonable that a rendering of the child could adequately represent
     * that resource. If the resource is a Manifest, the default Sequence will be used; if the resource is a Canvas, the first image Annotation
     * will be used; if the resource is an Annotation, the ContentResource will also be used.
     * @return string[]
     */
    public function getRenderingUrlsForFormat(string $format, bool $useChildResources = true) {
        $renderingUrls = [];
        if (empty($format)) {
            return $renderingUrls;
        }
        if ($this->rendering!=null && is_array($this->rendering)) {
            if (JsonLdProcessor::isSequentialArray($this->rendering)) {
                foreach ($this->rendering as $rendering) {
                    if (!is_array($rendering)) {
                        continue;
                    }
                    if (array_key_exists("format", $rendering) && $rendering["format"] == $format && array_key_exists("@id", $rendering)) {
                        $renderingUrls[] = $rendering["@id"];
                    }
                }
            } else {
                if (array_key_exists("format", $this->rendering) && $this->rendering["format"] == $format && array_key_exists("@id", $this->rendering)) {
                    $renderingUrls[] = $this->rendering["@id"];
                }
            }
        }
        if (empty($renderingUrls) && $useChildResources) {
            if ($this instanceof Manifest && !empty($this->getSequences())) {
                $renderingUrls = $this->getSequences()[0]->getRenderingUrlsForFormat($format);
            }
            elseif ($this instanceof Canvas && !empty($this->getImages())) {
                $renderingUrls = $this->getImages()[0]->getRenderingUrlsForFormat($format);
            }
            elseif ($this instanceof Annotation && $this->getResource()!=null) {
                $renderingUrls = $this->getResource()->getRenderingUrlsForFormat($format);
            }
        }
        return $renderingUrls;
    }

}
