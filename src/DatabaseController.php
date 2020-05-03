<?php


namespace TUDublin;

require_once __DIR__ . '\..\env\dbConstants.php';

use TUDublin\dbObjects\
{
    Coffeeshop,
    CoffeeshopRepository,
    CoffeeshopAddress,
    CoffeeshopAddressRepository,
    CoffeeshopComment,
    CoffeeshopCommentRepository,
    CoffeeshopPaidContent,
    CoffeeshopPaidContentRepository,
    CoffeeshopReview,
    CoffeeshopReviewRepository,
    CoffeeshopMenu,
    CoffeeshopMenuRepository,
    MenuItem,
    MenuItemRepository,
    Picture,
    PictureRepository,
    User,
    UserRepository
};

class DatabaseController
{

    private $csRepo;
    private $csAddressRepo;
    private $csCommentRepo;
    private $csPaidContentRepo;
    private $csReviewRepo;
    private $csMenuRepo;
    private $menuItemRepo;
    private $pictureRepo;
    private $usersRepo;

    public function __construct()
    {
        $this->csRepo = new CoffeeshopRepository();
        $this->csAddressRepo = new CoffeeshopAddressRepository();
        $this->csCommentRepo = new CoffeeshopCommentRepository();
        $this->csReviewRepo = new CoffeeshopReviewRepository();
        $this->menuItemRepo = new MenuItemRepository();
        $this->pictureRepo = new PictureRepository();
        $this->usersRepo = new UserRepository();
    }


    public function getCoffeeshops()
    {
        return $this->csRepo->findAll();
    }

    public function getCoffeeshop($csid)
    {
        return $this->csRepo->find($csid);
    }

    public function getAllReviews()
    {
        return $this->csReviewRepo->findAll();
    }

    public function getAllReviewsFor($coffeeshop_id)
    {
        return $this->csReviewRepo->getAllReviewsForCoffeeshop($coffeeshop_id);
    }


}