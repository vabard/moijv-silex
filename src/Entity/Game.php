<?php


namespace Entity;

/**
 * Description of Game
 *
 * @author vassilina
 */
class Game {

    /**
     *  Id of the game
     * @var integer
     */
    private $id;
    
    /**
     *  Title of the game
     * @var string
     */
    private $title;
    
    /**
     *  Image of the game
     * @var string
     */
    private $image;
    
    /**
     *  User of the game
     * @var \Entity\User
     */
    private $user;
    
    /**
     *  Category_id of the game
     * @var \Entity\Category
     */
    private $category;
    
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getImage() {
        return $this->image;
    }

    public function getUser() {
        return $this->user;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setUser(\Entity\User $user) {
        $this->user = $user;
    }

    public function setCategory(\Entity\Category $category) {
        $this->category = $category;
    }





    
}
