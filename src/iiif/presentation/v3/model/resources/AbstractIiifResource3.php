<?php
namespace iiif\presentation\v3\model\resources;

use iiif\context\IRI;
use iiif\context\JsonLdProcessor;
use iiif\context\Keywords;

abstract class AbstractIiifResource3 extends AbstractIiifEntity
{
   
    protected $id;
    protected $type;
    protected $behavior;
    
    protected $label;
    protected $metadata;
    protected $summary;
    protected $thumbnail;
    protected $requiredStatement;
    protected $rights;
    
    protected $seeAlso;
    protected $service;
    protected $logo;
    protected $homepage;
    protected $rendering;
    /**
     * @var Collection3[]
     */
    protected $partOf;
    
    /**
     * 
     * @param string|array $resource URI of the IIIF manifest, json string representation of the manifest or decoded json array
     * @return \iiif\presentation\v3\model\resources\AbstractIiifResource3 | NULL
     */
    public static function loadIiifResource($resource)
    {
        if (is_string($resource) && IRI::isAbsoluteIri($resource)) {
            $resource = file_get_contents($resource);
        }
        if (is_string($resource)) {
            $resource = json_decode($resource, true);
        }
        if (JsonLdProcessor::isDictionary($resource)) {
            $r = self::parseDictionary($resource);
            echo "debug";
            return $r;
        }
        return null;
    }
    
    protected function getTranslationFor($dictionary, string $language = null) {
        if ($dictionary == null || !JsonLdProcessor::isDictionary($dictionary)) {
            return null;
        }
        if ($language!=null && array_key_exists($language, $dictionary)) {
            return $dictionary[$language];
        } elseif (array_key_exists(Keywords::NONE, $dictionary)) {
            return $dictionary[Keywords::NONE];
        } elseif ($language == null) {
            return reset($dictionary);
        }
        return null;
    }
    
    public function getLabelTranslated(string $language = null) {
        return $this->getTranslationFor($this->label, $language);
    }
    
    public function getMetadataForLabel($label, string $language = null) {
        if ($this->metadata != null) {
            $selectedMetaDatum = null;
            foreach ($this->metadata as $metadatum) {
                foreach ($metadatum["label"] as $lang=>$labels) {
                    if (array_search($label, $labels)!==false) {
                        $selectedMetaDatum = $metadatum;
                        break 2;
                    }
                }
            }
            if ($selectedMetaDatum != null) {
                $v = $this->getTranslationFor($metadatum["value"], $language);
                return $this->getTranslationFor($metadatum["value"], $language);
            }
        }
        return null;
    }
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getBehavior()
    {
        return $this->behavior;
    }

    /**
     * @return array
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return array
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @return string
     */
    public function getRequiredStatement()
    {
        return $this->requiredStatement;
    }

    /**
     * @return string
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @return mixed
     */
    public function getSeeAlso()
    {
        return $this->seeAlso;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @return mixed
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @return mixed
     */
    public function getRendering()
    {
        return $this->rendering;
    }

    /**
     * @return Collection3[]
     */
    public function getPartOf()
    {
        return $this->partOf;
    }

    /**
     * @return array
     */
    public function getOriginalJsonArray()
    {
        return $this->originalJsonArray;
    }

    
}




