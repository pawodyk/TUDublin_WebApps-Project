<?php


namespace TUDublin;


use TUDublin\dbObjects\CoffeeshopAddressRepository;
use TUDublin\dbObjects\CoffeeshopCommentRepository;
use TUDublin\dbObjects\CoffeeshopOwnerRepository;
use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\CoffeeshopReviewRepository;
use TUDublin\dbObjects\MenuItemRepository;
use TUDublin\dbObjects\PictureRepository;
use TUDublin\dbObjects\UserRepository;

class MenuController extends Controller
{
    private $csRepo;
    private $csAddressRepo;
    private $csCommentRepo;
    private $csOwnerRepo;
    private $csReviewRepo;
    private $menuItemRepo;
    private $pictureRepo;
    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->csRepo = new CoffeeshopRepository();
//        $this->csAddressRepo = new CoffeeshopAddressRepository();
//        $this->csCommentRepo = new CoffeeshopCommentRepository();
//        $this->csOwnerRepo = new CoffeeshopOwnerRepository();
//        $this->csReviewRepo = new CoffeeshopReviewRepository();
        $this->menuItemRepo = new MenuItemRepository();
//        $this->pictureRepo = new PictureRepository();
//        $this->userRepo = new UserRepository();
    }

    public function menuControlPage()
    {
//        $ownerId = filter_input(INPUT_GET, 'owner_id');

        $ownerId = $_SESSION['owner_id'];

        $template = 'menucontrolpanel.html.twig';
        $args = [
            'coffeeshops'=> $this->csRepo->getAllCoffeeshopsFor($ownerId),
            'menuitems'=> $this->menuItemRepo->getAllMenuItemsForOwner($ownerId),
        ];

        $this->renderPage($template, $args);
    }

    public function addMenuItemPage(){

    }

    public function submitNewMenuItem(){

    }

    public function assignMenuToCoffeeshop(){

    }



    /* DEPRECATED FUNCTIONS TODO remove after cleanup */

    /**
     * @deprecated
     */
    public function getMenuItems($menuId)
    {
        return $this->menuItemRepo->getAllMenuItems($menuId);
    }

    /**
     * @deprecated
     */
    public function getMenuItemsForOwner($ownerId)
    {
        return $this->menuItemRepo->getAllMenuItemsForOwner($ownerId);
    }
}