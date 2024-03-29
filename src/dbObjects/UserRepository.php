<?php


namespace TUDublin\dbObjects;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;


class UserRepository extends DatabaseTableRepository
{
    public function getAllPerUserRole($role){
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `user` WHERE `user_role` = :role';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':role', $role, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();


    }

    public function getUser($username)
    {
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `user` WHERE `username` = :uname';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':uname', $username, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetch();
    }
}