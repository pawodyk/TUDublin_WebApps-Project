<?php


namespace TUDublin\dbObjects;


class CoffeeshopReview
{

    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `coffeeshopreview` (
  `id` int NOT NULL AUTO_INCREMENT,
  `coffeeshop_id` int NOT NULL,
  `title` VARCHAR(120) NOT NULL,
  `review` text NOT NULL,
  `rating` tinyint NOT NULL DEFAULT '0',
  `expense` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_REVIEW_COFFEESHOP` (`coffeeshop_id`),
  CONSTRAINT `FK_REVIEW_COFFEESHOP` FOREIGN KEY (`coffeeshop_id`) REFERENCES `coffeeshop` (`id`)
);
HERE;

    private $id;
    private $coffeeshop_id;
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
    public function getCoffeeshopId()
    {
        return $this->coffeeshop_id;
    }

    /**
     * @param mixed $coffeeshop_id
     */
    public function setCoffeeshopId($coffeeshop_id)
    {
        $this->coffeeshop_id = $coffeeshop_id;
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