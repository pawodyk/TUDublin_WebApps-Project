<?php


namespace TUDublin\dbObjects;


class CoffeeshopReview
{

    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `coffeeshopreview` (
  `id` int NOT NULL AUTO_INCREMENT,
  `coffeeshop` int NOT NULL,
  `title` varchar(50) NOT NULL,
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rating` tinyint NOT NULL DEFAULT '0',
  `expense` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_REVIEW_COFFEESHOP` (`coffeeshop`),
  CONSTRAINT `FK_REVIEW_COFFEESHOP` FOREIGN KEY (`coffeeshop`) REFERENCES `coffeeshop` (`id`)
);
HERE;

    private $id;
    private $coffeeshop;
    private $title;
    private $review;
    private $rating;
    private $expense;

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
    public function getCoffeeshop()
    {
        return $this->coffeeshop;
    }

    /**
     * @param mixed $coffeeshop
     */
    public function setCoffeeshop($coffeeshop)
    {
        $this->coffeeshop = $coffeeshop;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param mixed $review
     */
    public function setReview($review)
    {
        $this->review = $review;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getExpense()
    {
        return $this->expense;
    }

    /**
     * @param mixed $expense
     */
    public function setExpense($expense)
    {
        $this->expense = $expense;
    }



}