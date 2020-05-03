<?php


namespace TUDublin\dbObjects;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;


class CoffeeshopReviewRepository extends DatabaseTableRepository
{

    public function getAllReviewsForCoffeeshop($coffeeshop_id){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `coffeeshopreview` WHERE `coffeeshop_id` = :csid';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':csid', $coffeeshop_id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();

    }

}