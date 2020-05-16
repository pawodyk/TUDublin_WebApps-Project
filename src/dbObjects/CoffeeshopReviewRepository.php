<?php


namespace TUDublin\dbObjects;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;


class CoffeeshopReviewRepository extends DatabaseTableRepository
{
    /**
     * @param $coffeeshop_id
     * @return array of Coffeeshop objects
     * the return set is ordered by data and then by id,
     * although the id should be enough to get the result set to be in order,
     * it make more sense to sort it by data first
     */
    public function getAllReviewsForCoffeeshop($coffeeshop_id)
    {
        $db = new DatabaseManager();
        $conn = $db->getDbh();

        $sql = 'SELECT * FROM `coffeeshopreview` WHERE `coffeeshop_id` = :csid ORDER BY `review_date` DESC, `id` DESC ';

        $statement = $conn->prepare($sql);
        $statement->bindParam(':csid', $coffeeshop_id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->getClassNameForDbRecords());
        $statement->execute();

        return $statement->fetchAll();

    }

}