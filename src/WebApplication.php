<?php

namespace TUDublin;


class WebApplication
{

    private $mainCon;
    private $loginCon;
    private $coffeeshopCon;
    private $menuCon;
    private $reviewCon;
    private $commentCon;
    private $adminCon;
    private $pictureCon;
    private $ownerCon;

    public function __construct()
    {
        $this->mainCon = new MainController();
        $this->loginCon = new LoginController();
        $this->coffeeshopCon = new CoffeeshopController();
        $this->menuCon = new MenuController();
        $this->reviewCon = new ReviewController();
        $this->commentCon = new CommentController();
        $this->adminCon = new AdminController();
        $this->pictureCon = new PictureController();
        $this->ownerCon = new OwnerController();

    }

    public function run()
    {
        $page = filter_input(INPUT_GET, 'page');

        switch ($page) {
            case 'login':
                $this->loginCon->login();
                break;
            case 'logout':
                $this->loginCon->logout();
                break;
            case 'shop':
                $this->coffeeshopCon->coffeeshopPage();
                break;
            case 'shops':
                $this->coffeeshopCon->coffeeshopsListPage();
                break;
            case 'review':
                $this->reviewCon->reviewPage();
                break;
            case 'reviews':
                $this->reviewCon->reviewsListPage();
                break;
            case 'submit_comment':
                $this->commentCon->addComment();
                break;

            case 'admin':
                if ($this->loginCon->verifyAccess('ROLE_ADMIN')) {
                    $this->adminControls();
                } else {
                    $this->mainCon->accessDeniedPage();
                }
                break;

            case 'new_review':
            case 'submit_review':
            case 'comments':
            case 'reject_comment':
            case 'approve_comment':
                if ($this->loginCon->verifyAccess('ROLE_STAFF')) {
                    $this->userControls($page);
                } else {
                    $this->mainCon->accessDeniedPage();
                }
                break;

            case 'edit_coffeeshop':
            case 'submit_coffeeshop_update':
            case 'owners_shops':
            case 'owners_profile':
            case 'edit_owner':
            case 'submit_edit_owner':
            case 'menu_control':
            case 'upload_coffeeshop_picture':
            case 'asign_menu_coffeeshop':
                if ($this->loginCon->verifyAccess('ROLE_STAFF') || $this->loginCon->verifyAccess('ROLE_SHOP')) {
                    $this->ownerControlls($page);
                } else {
                    $this->mainCon->accessDeniedPage();
                }
                break;

            case 'home':
            default:
                $this->mainCon->homePage();
                break;
        }
    }

    public function ownerControlls($page)
    {
        switch ($page) {
            case 'edit_coffeeshop':
                $this->coffeeshopCon->editCoffeeshopPage();
                break;
            case 'submit_coffeeshop_update':
                $this->coffeeshopCon->updateCoffeeshop();
                break;
            case 'owners_shops':
                $this->coffeeshopCon->ownersCoffeeshopsPage();
                break;
            case 'owners_profile':
                $this->ownerCon->ownerProfilePage();
                break;
            case 'edit_owner':
                $this->ownerCon->editOwnerProfilePage();
                break;
            case 'submit_edit_owner':
                $this->ownerCon->updateOwnerProfile();
                break;
            case 'menu_control':
                $this->menuCon->menuControlPage();
                break;
            case 'upload_coffeeshop_picture':
                $this->pictureCon->savePictureForCoffeeshop();
                break;
            case 'asign_menu_coffeeshop':
                $this->menuCon->assignMenuToCoffeeshop();
                break;
        }
    }


    public function userControls($page)
    {

        switch ($page) {
            case 'new_review':
                $this->reviewCon->newReviewPage();
                break;
            case 'submit_review':
                $this->reviewCon->addReview();
                break;
            case 'comments':
                $this->commentCon->commentsReviewPage();
                break;
            case 'approve_comment':
                $this->commentCon->approveComment();
                break;
            case 'reject_comment':
                $this->commentCon->deleteComment();
                break;
        }
    }

    public function adminControls()
    {

        $action = filter_input(INPUT_GET, 'action');
        if (!$action) {
            $action = filter_input(INPUT_POST, 'action');
        }

        switch ($action) {
            case 'remove_user':
                $this->adminCon->deleteUser();
                break;
            case 'edit_user':
                $this->adminCon->editUserPage();
                break;
            case 'update_user':
                $this->adminCon->updateUser();
                break;
            case 'reset_password':
                $this->adminCon->editPasswordPage();
                break;
            case 'submit_password':
                $this->adminCon->changeUserPassword();
                break;
            case 'new_user':
                $this->adminCon->newUserPage();
                break;
            case 'submit_new_user':
                $this->adminCon->addUser();
                break;
            case 'join_owner':
                $this->adminCon->addOwnerProfile();
                break;
            case 'coffeeshop_owners':
                $this->adminCon->coffeeshopOwnersSetupPage();
                break;
            case 'update_coffeeshop_owner':
                $this->adminCon->setOwnerOfCoffeeshop();
                break;
            default:
                $this->adminCon->adminPage();
        }
    }

}
