<?php

namespace TUDublin;


class WebApplication
{

    private $loginController;
    private $mainController;
    private $dbController;

    public function __construct()
    {
        $this->mainController = new MainController();
        $this->loginController = new LoginController();
        $this->dbController = new DatabaseController();

    }

    public function run()
    {
        $page = filter_input(INPUT_GET, 'page');

        switch ($page) {
            case 'login':
                $this->loginController->login();
                break;
            case 'logout':
                $this->loginController->logout();
                break;
            case 'shop':
                $this->mainController->shop();
                break;
            case 'shops':
                $this->mainController->shops();
                break;
            case 'admin':
                $this->adminControls();
                break;
            case 'new_review':
                $this->mainController->newReview();
                break;
            case 'submit_review':
                $this->dbController->addReview();
                break;
            case 'submit_comment':
                $this->dbController->addComment();
                break;
            case 'comments':
                $this->mainController->reviewComments();
                break;
            case 'review':
                $this->mainController->viewReview();
                break;
            case 'test':
                $this->mainController->test();
                break;
            case 'home':
            default:
                $this->mainController->home();
                break;
        }
    }

    public function adminControls()
    {
        if ($this->loginController->verifyAccess('ROLE_ADMIN')) {

            $action = filter_input(INPUT_GET, 'action');
            if (!$action) {
                $action = filter_input(INPUT_POST, 'action');
            }

            switch ($action) {
                case 'search_user':
                    //$this->mainController->searchUser();
                    break;
                case 'remove_user':
                    $this->dbController->deleteUser();
                    break;
                case 'edit_user':
                    $this->mainController->editUser();
                    break;
                case 'update_user':
                    $this->dbController->updateUser();
                    break;
                case 'reset_password':
                    $this->mainController->editPassword();
                    break;
                case 'submit_password':
                    $this->dbController->changeUserPassword();
                    break;
                case 'new_user':
                    $this->mainController->newUser();
                    break;
                case 'submit_new_user':
                    $this->dbController->addUser();
                    break;
                case 'join_owner':
                    $this->dbController->addOwnerProfile();
                    break;
                case 'coffeeshop_owners':
                    $this->mainController->coffeeshopOwnersSetup();
                    break;
                case 'update_coffeeshop_owner':
                    $this->dbController->setOwnerOfCoffeeshop();
                    break;
                default:
                    $this->mainController->admin();
            }
        }
        else {
            $this->mainController->accessDenied();
        }
    }



}
