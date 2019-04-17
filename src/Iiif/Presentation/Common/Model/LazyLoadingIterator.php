<?php
namespace Ubl\Iiif\Presentation\Common\Model;

class LazyLoadingIterator implements \Iterator {
    
    /**
     * @var AbstractIiifEntity
     */
    protected $entity;
    
    /**
     * @var string
     */
    protected $field;
    
    /**
     * @var AbstractIiifEntity[]
     */
    protected $items;
    
    /**
     * @var integer
     */
    protected $position = 0;
    
    /**
     * 
     * @param AbstractIiifEntity $entity
     * @param string $field
     */
    public function __construct(&$entity, $field) {
        $htis->entity = &$entity;
        $this->field = $field;
    }

    public function next() {
        $this->position++;
    }

    public function valid() {
        return $this->$items != null && $this->$position < sizeof($this->$items); 
    }

    public function current() {
        if (!$this->valid()) {
            return null;
        }
        if ($this->items[$this->position]->isLinkedResource()) {
            // TODO lazy load resource
        }
        return $this->items[$this->position];
    }

    public function rewind() {
        $this->position = 0;
    }

    public function key() {
        return $this->valid() ? $this->current()->getId() : null;
    }

}

