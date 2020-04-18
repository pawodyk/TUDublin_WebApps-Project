<?php


namespace TUDublin\dbObjects;


class CoffeeshopPaidContent
{
    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `coffeeshoppaidcontent` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner` int DEFAULT NULL,
  `summary` text,
  `menu_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_PAIDCONTENT_OWNER` (`owner`),
  KEY `FK_PAIDCONTENT_MENU` (`menu_id`),
  CONSTRAINT `FK_PAIDCONTENT_MENU` FOREIGN KEY (`menu_id`) REFERENCES `manulist` (`menu_id`),
  CONSTRAINT `FK_PAIDCONTENT_OWNER` FOREIGN KEY (`owner`) REFERENCES `ownerdetails` (`id`)
);
HERE;

    private $id;
    private $owner;
    private $summary;
    private $menu_id;

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
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
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
    public function getMenuId()
    {
        return $this->menu_id;
    }

    /**
     * @param mixed $menu_id
     */
    public function setMenuId($menu_id)
    {
        $this->menu_id = $menu_id;
    }



}