<?php


namespace TUDublin\dbObjects;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;


class CoffeeshopRepository extends DatabaseTableRepository
{
    public function getAllCoffeeshopsForUser($user_id){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `coffeeshop` WHERE `owner_id` = :userid';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':userid', $user_id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();

    }

    public function getAverageRating($coffeeshop_id){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT avg(rating) FROM `coffeeshopreview` WHERE `coffeeshop_id`= :coffeeshopid';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':coffeeshopid', $coffeeshop_id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();

    }
}