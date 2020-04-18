<?php


namespace TUDublin\dbObjects;


class MenuList
{
    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `manulist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `FK_MENU_ITEM` (`item_id`),
  CONSTRAINT `FK_MENU_ITEM` FOREIGN KEY (`item_id`) REFERENCES `manuitem` (`id`)
);
HERE;

    private $id;
    private $menu_id;
    private $item_id;

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

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param mixed $item_id
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;
    }



}