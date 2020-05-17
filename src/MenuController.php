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

    public function editMenuPage()
    {
        $csid = filter_input(INPUT_GET, 'csid');
        $cs = $this->csRepo->find($csid);
        $cs_mi = $this->menuItemRepo->getAllMenuItems($cs->getMenuId());
        $owners_mi = $this->menuItemRepo->getAllMenuItemsForOwner($cs->getOwnerId());


        $template = 'ownersmenuitems.html.twig';
        $args = [
            'coffeeshop'=> $cs,
            'menuitems'=> $cs_mi,
            'ownersitems'=>$owners_mi,
        ];


        $this->renderPage($template, $args);
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