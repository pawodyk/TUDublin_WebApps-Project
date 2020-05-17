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
  `owner_id` int NULL,
  `menu_id` int NULL,
  `picture_id` int NULL,
  PRIMARY KEY (`id`),
  KEY `FK_COFFEESHOP_ADDRESS` (`address_id`),
  KEY `FK_COFFEESHOP_OWNER` (`owner_id`),
  KEY `FK_COFFEESHOP_PICTURE` (`picture_id`),
  INDEX `KEY_COFFEESHOPMENU` (`menu_id`),
  CONSTRAINT `FK_COFFEESHOP_ADDRESS` FOREIGN KEY (`address_id`) REFERENCES `coffeeshopaddress` (`id`),
  CONSTRAINT `FK_COFFEESHOP_OWNER` FOREIGN KEY (`owner_id`) REFERENCES `coffeeshopowner` (`id`),
  CONSTRAINT `FK_COFFEESHOP_PICTURE` FOREIGN KEY (`picture_id`) REFERENCES `picture` (`id`)
);
HERE;

    private $id;
    private $name;
    private $summary;
    private $address_id;
    private $owner_id;
    private $menu_id;
    private $picture_id;


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

    public function getAddress()
    {
        $csAddressRepo = new CoffeeshopAddressRepository();

        return $csAddressRepo->find($this->address_id);
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

    public function getOwner()
    {
        $csOwnerRepo = new CoffeeshopOwnerRepository();

        return $csOwnerRepo->find($this->owner_id);
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

    public function getMenuList()
    {
        $csMenuRepo = new MenuItemRepository();

        return $csMenuRepo->getAllMenuItems($this->menu_id);
    }

    /**
     * @return mixed
     */
    public function getPictureId()
    {
        return $this->picture_id;
    }

    /**
     * @param mixed $picture_id
     */
    public function setPictureId($picture_id)
    {
        $this->picture_id = $picture_id;
    }

    public function getPicture()
    {
        $pictureRepo = new PictureRepository();

        return $pictureRepo->find($this->picture_id);
    }

    public function getAverageRating()
    {
        $csRepo = new CoffeeshopRepository();

        return ceil($csRepo->getAverageRating($this->getId()));
    }

    public function getAverageExpense()
    {
        $csRepo = new CoffeeshopRepository();

        return ceil($csRepo->getAverageExpense($this->getId()));
    }

}