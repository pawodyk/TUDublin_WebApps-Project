<?php


namespace TUDublin\dbObjects;


class Coffeeshop
{
    const CREATE_TABLE_SQL =
<<<HERE
CREATE TABLE `coffeeshop` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `summary` text NULL,
  `address_id` int NULL,
  `paid_content_id` int NULL,
  PRIMARY KEY (`id`),
  KEY `FK_COFFEESHOP_ADDRESS` (`address_id`),
  KEY `FK_COFFEESHOP_PAIDCONTENT` (`paid_content_id`),
  CONSTRAINT `FK_COFFEESHOP_ADDRESS` FOREIGN KEY (`address_id`) REFERENCES `coffeeshopaddress` (`id`),
  CONSTRAINT `FK_COFFEESHOP_PAIDCONTENT` FOREIGN KEY (`paid_content_id`) REFERENCES `coffeeshoppaidcontent` (`id`)
);
HERE;

    private $id;
    private $name;
    private $summary;
    private $address_id;
    private $paid_content_id;

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
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getAddressId()
    {
        return $this->address_id;
    }

    /**
     * @param mixed $address_id
     */
    public function setAddressId($address_id)
    {
        $this->address_id = $address_id;
    }

    /**
     * @return mixed
     */
    public function getPaidContentId()
    {
        return $this->paid_content_id;
    }

    /**
     * @param mixed $paid_content_id
     */
    public function setPaidContentId($paid_content_id)
    {
        $this->paid_content_id = $paid_content_id;
    }



}