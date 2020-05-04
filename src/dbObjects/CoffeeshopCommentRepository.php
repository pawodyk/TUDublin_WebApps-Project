<?php


namespace TUDublin\dbObjects;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;

class CoffeeshopCommentRepository extends DatabaseTableRepository
{
    public function getAllCommentsForCoffeeshop($coffeeshop_id){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `coffeeshopcomment` WHERE `coffeeshop_id` = :csid';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':csid', $coffeeshop_id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();

    }

    public function getAllNonPublishedComments(){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `coffeeshopcomment` WHERE  `is_published` = FALSE ';

        $statement = $conn->query($sql);

        return $statement->fetchAll();
    }
}