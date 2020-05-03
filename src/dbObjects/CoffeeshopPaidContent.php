<?php


namespace TUDublin\dbObjects;

/**
 * Class CoffeeshopPaidContent
 * @package TUDublin\dbObjects
 * @deprecated
 */
class CoffeeshopPaidContent
{
    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `coffeeshoppaidcontent` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_name` varchar(120) NULL,
  `owner_id` int NOT NULL,
  `summary` varchar(1000) NULL,
  `menu_id` int NULL,
  PRIMARY KEY (`id`),
  KEY `FK_PAIDCONTENT_USER` (`owner_id`),
  KEY `FK_PAIDCONTENT_MENU` (`menu_id`),
  CONSTRAINT `FK_PAIDCONTENT_USER` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_PAIDCONTENT_MENU` FOREIGN KEY (`menu_id`) REFERENCES `coffeeshopmenu` (`id`)
);
HERE;

    private $id;
    private $owner_name;
    private $owner_id;
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
    public function getOwnerName()
    {
        return $this->owner_name;
    }

    /**
     * @param mixed $owner_name
     */
    public function setOwnerName($owner_name)
    {
        $this->owner_name = $owner_name;
    }

    /**
     * @return mixed
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param mixed $owner_id
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
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

    public function getMenu(){
        $csMenuRepo = new CoffeeshopMenuRepository();
        $menu = $csMenuRepo->find($this->getMenuId());
        return $menu->getMenuItemsArray();
    }

}