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
  `rating` tinyint NOT NULL DEFAULT 3,
  `expense` tinyint NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`),
  KEY `FK_REVIEW_COFFEESHOP` (`coffeeshop_id`),
  CONSTRAINT `FK_REVIEW_COFFEESHOP` FOREIGN KEY (`coffeeshop_id`) REFERENCES `coffeeshop` (`id`)
);
HERE;

    private $id;
    private $coffeeshop_id;
    private $title;
    private $review = 3;
    private $rating = 3;
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
        if (is_integer($rating)){
            if ($rating < 6 && $rating > 0) {
                $this->rating = $rating;
            }
        } else {
            $this->rating = 3;
        }
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
        if (is_integer($expense)){
            if ($expense < 6 && $expense > 0) {
                $this->expense = $expense;
            }
        } else {
            $this->expense = 3;
        }
    }

}