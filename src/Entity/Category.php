<?php

namespace Entity;

/**
 * Description of Category
 *
 * @author vassilina
 */
class Category {
    
    /**
     *  Id de category
     * @var integer 
     */
    private $id;
    
    /**
     *  Name de category
     * @var string 
     */
    private $name;
    
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
