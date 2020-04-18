<?php


namespace TUDublin\dbObjects;


class CoffeeshopComment
{
    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `coffeeshopcomment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `coffeeshop` int NOT NULL DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_COMMENT_COFFEESHOP` (`coffeeshop`),
  CONSTRAINT `FK_COMMENT_COFFEESHOP` FOREIGN KEY (`coffeeshop`) REFERENCES `coffeeshop` (`id`)
);
HERE;

    private $id;
    private $name;
    private $message;
    private $coffeeshop;
    private $is_published;

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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCoffeeshop()
    {
        return $this->coffeeshop;
    }

    /**
     * @param mixed $coffeeshop
     */
    public function setCoffeeshop($coffeeshop)
    {
        $this->coffeeshop = $coffeeshop;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * @param mixed $is_published
     */
    public function setIsPublished($is_published)
    {
        $this->is_published = $is_published;
    }



}