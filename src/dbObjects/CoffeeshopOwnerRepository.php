<?php


namespace TUDublin\dbObjects;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;


class CoffeeshopOwnerRepository extends DatabaseTableRepository
{
    public function getOwnerByUserId($userId)
    {
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `coffeeshopowner` WHERE `user_id` = :userId';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':userId', $userId, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetch();
    }
}