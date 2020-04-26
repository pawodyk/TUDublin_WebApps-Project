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
        $this->csPaidContentRepo = new CoffeeshopPaidContentRepository();
        $this->csReviewRepo = new CoffeeshopReviewRepository();
        $this->csMenuRepo = new CoffeeshopMenuRepository();
        $this->menuItemRepo = new MenuItemRepository();
        $this->pictureRepo = new PictureRepository();
        $this->usersRepo = new UserRepository();
    }


    public function getCoffeeshops()
    {
        $returnValue = [];
        $coffeeshops = $this->csRepo->findAll();

        foreach ($coffeeshops as $cs) {
            $returnValue[] = [
                'sc'=>$cs,
                'pc'=>$cs->getPaidContent()
            ];
        }

        return $returnValue;
    }

    public function getCoffeeshop($csid){

        $coffeeshop = $this->csRepo->find($csid);

        return [
            'sc'=>$coffeeshop,
            'pc'=>$coffeeshop->getPaidContent(),
        ];

    }

    public function getAllReviews(){
        return $this->csReviewRepo->findAll();
    }

    public function getAllReviewsFor($coffeeshop_id){
        return $this->csReviewRepo->searchByColumn('coffeeshop_id', $coffeeshop_id);
    }


}