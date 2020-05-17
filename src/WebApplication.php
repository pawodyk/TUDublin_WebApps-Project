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

    }

    public function run()
    {
        $page = filter_input(INPUT_GET, 'page');
        $action = filter_input(INPUT_GET, 'action');
        if (!$action) {
            $action = filter_input(INPUT_POST, 'action');
        }


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
            case 'submit_comment':
                $this->commentCon->addComment();
                break;
            case 'admin':
                $this->adminControls($action);
                break;
            case 'new_review':
            case 'submit_review':
            case 'comments':
                $this->userControls($page);
                break;
            case 'edit_coffeeshop':
                if ($this->loginCon->verifyAccess('ROLE_STAFF') || $this->loginCon->verifyAccess('ROLE_SHOP')){
                    $this->coffeeshopCon->editCoffeeshopPage();
                } else {
                    $this->mainCon->accessDeniedPage();
                }
                break;
            case 'submit_coffeeshop_update':
                $this->coffeeshopCon->updateCoffeeshop();
                break;
            case 'home':
            default:
                $this->mainCon->homePage();
                break;
        }
    }

    public function userControls($page){
        if ($this->loginCon->verifyAccess('ROLE_STAFF')){
            switch ($page){
                case 'new_review':
                    $this->reviewCon->newReviewPage();
                    break;
                case 'submit_review':
                    $this->reviewCon->addReview();
                    break;
                case 'comments':
                    $this->commentCon->commentsReviewPage();
                    break;
            }
        } else {
            $this->mainCon->accessDeniedPage();
        }
    }

    public function adminControls($action)
    {
        if ($this->loginCon->verifyAccess('ROLE_ADMIN')) {

            switch ($action) {
//                case 'search_user':
//                    break;
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
        else {
            $this->mainController->accessDeniedPage();
        }
    }



}
