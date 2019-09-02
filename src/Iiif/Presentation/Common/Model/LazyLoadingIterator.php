<?php
namespace Ubl\Iiif\Presentation\Common\Model;

use Ubl\Iiif\IiifException;

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
    public function __construct(&$entity, $field, &$items) {
        $htis->entity = &$entity;
        $this->field = $field;
        $this->items = &$items;
    }

    public function next() {
        $this->position++;
    }

    public function valid() {
        return $this->items != null && $this->position < sizeof($this->items); 
    }

    /**
     * @return AbstractIiifEntity
     */
    public function current() {
        if (!$this->valid()) {
            return null;
        }
        if ($this->items[$this->position]->isLinkedResource()) {
            try {
                $this->items[$this->position]->loadLazy();
            } catch (IiifException $ex) {
                $this->items[$this->position] = AbstractIiifEntity::loadIiifResource($this->items[$this->position]->getId());
                // TODO register resource
            }
        }
        return $this->items[$this->position];
    }

    public function rewind() {
        $this->position = 0;
    }

    public function key() {
        return $this->valid() ? $this->position : null;
    }

}

