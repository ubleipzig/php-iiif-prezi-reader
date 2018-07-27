<?php
namespace iiif\context;

class Term
{
    protected $term;
    protected $id;
    protected $expandedId;
    protected $type;
    protected $lanuage;
    protected $collectionContainer;
    protected $context;
    
    public function __construct($term, $id, $expandedId, $type, $language, $collectionContainer, $context) {
        $this->term = $term;
        $this->id = $id;
        $this->expandedId = $expandedId;
        $this->type = $type;
        $this->language = $language;
        $this->collectionContainer = $collectionContainer;
        $this->context = $context;
    }
    /**
     * @return mixed
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getExpandedId()
    {
        return $this->expandedId;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getLanuage()
    {
        return $this->lanuage;
    }

    /**
     * @return mixed
     */
    public function getCollectionContainer()
    {
        return $this->collectionContainer;
    }

    /**
     * @return JsonLdContext
     */
    public function getContext()
    {
        return $this->context;
    }
}

