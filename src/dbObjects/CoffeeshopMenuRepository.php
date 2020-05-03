<?php


namespace TUDublin\dbObjects;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;

/**
 * Class CoffeeshopMenuRepository
 * @package TUDublin\dbObjects
 * @deprecated
 */
class CoffeeshopMenuRepository extends DatabaseTableRepository
{
    public function getAllMenusForUser($user_id){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `coffeeshopmenu` WHERE `owner_id` = :userid';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':userid', $user_id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();

    }
}