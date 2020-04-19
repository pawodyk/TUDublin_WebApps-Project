<?php


namespace TUDublin\dbObjects;


class CoffeeshopComment
{
    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `coffeeshopcomment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `coffeeshop_id` int NOT NULL,
  `name` varchar(60) NULL,
  `message` varchar(500) NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_COMMENT_COFFEESHOP` (`coffeeshop_id`),
  CONSTRAINT `FK_COMMENT_COFFEESHOP` FOREIGN KEY (`coffeeshop_id`) REFERENCES `coffeeshop` (`id`)
);
HERE;

    private $id;
    private $coffeeshop_id;
    private $name;
    private $message;
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
    public function getCoffeeshopId()
    {
        return $this->coffeeshop_id;
    }

    /**
     * @param mixed $coffeeshop_id
     */
    public function setCoffeeshopId($coffeeshop_id)
    {
        $this->coffeeshop_id = $coffeeshop_id;
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