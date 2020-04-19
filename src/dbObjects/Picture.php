<?php


namespace TUDublin\dbObjects;


class Picture
{
    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `picture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `coffeeshop_id` int NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'photo',
  `filename` varchar(255) NOT NULL DEFAULT 'default.png',
  PRIMARY KEY (`id`),
  KEY `FK_PICTURE_COFFEESHOP` (`coffeeshop_id`),
  CONSTRAINT `FK_PICTURE_COFFEESHOP` FOREIGN KEY (`coffeeshop_id`) REFERENCES `coffeeshop` (`id`)
);
HERE;

    private $id;
    private $coffeeshop_id;
    private $name;
    private $filename;

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
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }


}