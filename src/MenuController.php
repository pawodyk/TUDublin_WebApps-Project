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
            //'menuitem_list'=> $this->menuItemRepo->getAllMenuItemsForOwner($ownerId),
            'menuIdlist'=>$this->menuItemRepo->getMenusOwnedBy($ownerId),
        ];

//        $this->debug($args);

        $this->renderPage($template, $args);
    }

    public function assignMenuToCoffeeshop(){
//        $coffeeshopId = filter_input(INPUT_GET, 'coffeeshopid');
//        $menuId = filter_input(INPUT_GET, 'menu_select');
//
//        if ($menuId == -1) {
//            $menuId = null;
//        }
//
//        $cs = $this->csRepo->find($coffeeshopId);
//        $cs->setMenuId($menuId);
//        $result = $this->csRepo->update($cs);
//
//        if ($result) {
//            if ($menuId) {
//                $this->logMessage('Menu ' . $menuId . ' now set for coffeeshop ID ' . $coffeeshopId);
//            } else {
//                $this->logMessage('Removed Menu from coffeeshop ID ' . $coffeeshopId);
//            }
//
//        } else {
//            $this->logError('Could not assign menu ID '. $menuId .' to Coffee Shop ID ' . $coffeeshopId);
//        }

        $this->logError('This feature is not yet available please contact CSR Staff to assign the menu to your coffeeshop.');
        $this->redirect('/', [
            'page'=>'menu_control',
        ]);
    }

//    public function addMenuItemPage(){
//        return;
//    }
//
//    public function submitNewMenuItem(){
//        return;
//    }





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