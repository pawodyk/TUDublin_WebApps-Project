<?php


namespace TUDublin\dbObjects;


class MenuItem
{
    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `menuitem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NULL,
  `item_name` varchar(60),
  `item_price` double(5,2),
  PRIMARY KEY (`id`),
  KEY `FK_MENUITEM_MENU` (`menu_id`),
  CONSTRAINT `FK_MENUITEM_COFFEESHOP` FOREIGN KEY (`menu_id`) REFERENCES `coffeeshop` (`menu_id`)
);
HERE;
    private $id;
    private $menu_id;
    private $item_name;
    private $item_price;

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
    public function getItemName()
    {
        return $this->item_name;
    }

    /**
     * @param mixed $item_name
     */
    public function setItemName($item_name)
    {
        $this->item_name = $item_name;
    }

    /**
     * @return mixed
     */
    public function getItemPrice()
    {
        return $this->item_price;
    }

    /**
     * @param mixed $item_price
     */
    public function setItemPrice($item_price)
    {
        $this->item_price = $item_price;
    }



}