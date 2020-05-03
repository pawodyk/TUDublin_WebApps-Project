<?php

namespace TUDublin\dbObjects;

class CoffeeshopOwner {

    const CREATE_TABLE_SQL =
<<<HERE
CREATE TABLE `coffeeshopowner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NULL,
  `user_id` int NOT NULL,
  `bio` varchar(500) NULL,
  PRIMARY KEY (`id`),
  KEY `FK_OWNER_USER` (`user_id`),
  CONSTRAINT `FK_OWNER_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
);
HERE;

    private $id;
    private $name;
    private $user_id;
    private $bio;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param mixed $bio
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    }



}