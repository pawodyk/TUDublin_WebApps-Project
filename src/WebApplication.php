<?php

namespace TUDublin;


class WebApplication {

    private $loginController;
    private $mainController;
    private $dbController;

    public $errors;

    public function __construct()
    {
        $this->mainController = new MainController();
        $this->loginController = new LoginController();
        $this->dbController = new DatabaseController();

        $GLOBALS['errors'] = [];
    }

    public function run() {
        $page = filter_input(INPUT_GET, 'page');


        switch($page){
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
            case 'add_review':

                break;
            case 'submit_review':
                $this->dbController->addReview();
                $this->mainController->shop();
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

    public function adminControls(){


        $action = filter_input(INPUT_GET, 'action');
        if (!$action) {
            $action = filter_input(INPUT_POST, 'action');
        }

        switch($action) {
            case 'search_user':
                //$this->mainController->searchUser();
                break;
            case 'remove_user':
                $this->dbController->deleteUser();
                unset($_GET);
                $this->mainController->admin();
                break;
            case 'edit_user':
                $this->mainController->editUser();
                break;
            case 'update_user':
                $this->dbController->updateUser();
                $this->mainController->admin();
                break;
            case 'reset_password':
                $this->mainController->editPassword();
                break;
            case 'submit_password':
                $this->dbController->changeUserPassword();
                $this->mainController->admin();
                break;
            case 'new_user':
                $this->mainController->newUser();
                break;
            case 'submit_new_user':
                $this->dbController->addUser();
                $this->mainController->admin();
                break;
            case 'join_owner':
                $this->dbController->addOwnerProfile();
                $this->mainController->admin();
                break;
            case 'coffeeshop_owners':
                $this->mainController->coffeeshopOwnersSetup();
                break;
            case 'update_coffeeshop_owner':
                $this->dbController->setOwnerOfCoffeeshop();
                $this->mainController->coffeeshopOwnersSetup();
                break;
            default:
                $this->mainController->admin();
        }
    }

}
