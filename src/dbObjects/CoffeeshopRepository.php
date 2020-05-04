<?php


namespace TUDublin\dbObjects;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;


class CoffeeshopRepository extends DatabaseTableRepository
{
    public function getAllCoffeeshopsFor($owner_id){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `coffeeshop` WHERE `owner_id` = :ownerid';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':ownerid', $owner_id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();

    }

    public function getTopCoffeeshops($limit){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = '
            SELECT cs.* FROM `coffeeshop` AS cs 
            JOIN `coffeeshopreview` AS r ON cs.id = r.coffeeshop_id 
            GROUP BY cs.id  
            ORDER BY AVG(r.rating) DESC 
            LIMIT :limit';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();

    }

    public function getAverageRating($coffeeshop_id){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT avg(rating) AS rating FROM `coffeeshopreview` WHERE `coffeeshop_id`= :coffeeshopid';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':coffeeshopid', $coffeeshop_id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();

        $dataset = $statement->fetch();

        return $dataset['rating'];

    }

    public function getAverageExpense($coffeeshop_id){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT avg(expense) AS expense FROM `coffeeshopreview` WHERE `coffeeshop_id`= :coffeeshopid';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':coffeeshopid', $coffeeshop_id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();

        $dataset = $statement->fetch();
        return $dataset['expense'];

    }
}