<?php


namespace TUDublin\dbObjects;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;

class MenuItemRepository extends DatabaseTableRepository
{
    public function getAllMenuItems($menuId)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = 'SELECT * FROM menuitem WHERE menu_id = :menuId';

        $statement = $connection->prepare($sql);
        $statement->bindParam(':menuId', $menuId, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();
    }

    public function deleteAllMenusForCoffeeshop($menuId){
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = 'DELETE FROM menuitem WHERE menu_id = :menuId';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':menuId', $menuId, \PDO::PARAM_INT);
        $statement->execute();
    }
}