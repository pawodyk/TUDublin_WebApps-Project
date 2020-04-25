<?php


namespace TUDublin\dbObjects;


class CoffeeshopMenu
{
    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `coffeeshopmenu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NULL,
  PRIMARY KEY (`id`),
  KEY `FK_MENU_USER` (`owner_id`),
  CONSTRAINT `FK_MENU_USER` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
);
HERE;

    private $id;
    private $owner_id;

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

    public function getMenuItemsArray(){
        $menuArray = [];

        $repo = new MenuItemRepository();
        $menuItems =  $repo->getAllMenuItems($this->getId());
        foreach ($menuItems as $item){
            $menuArray[] = [
                'name' => $item->getItemName(),
                'price' => $item->getItemPrice()
            ];
        }

        return $menuArray;
    }


}