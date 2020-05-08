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
//                $data_in = $_POST;
//                var_dump($_POST['username']);
//                var_dump($_POST['password']); die;

                $this->loginController->login();

                break;
            case 'logout':
//                print '<pre>';
//                var_dump($_SESSION);die;
                $this->loginController->logout();

                break;
            case 'shop':
                $csid = filter_input(INPUT_GET, 'csid');
                $this->mainController->shop($csid);
                break;
            case 'shops':
                $this->mainController->shops();
                break;
            case 'admin':
                $this->adminControls();
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
                $username = filter_input(INPUT_POST, 'username');
//                $this->mainController->searchUser($username);
                break;
            case 'remove_user':
                $this->dbController->deleteUser(filter_input(INPUT_GET, 'userid'));
                unset($_GET);
                $this->mainController->admin();
                break;
            case 'edit_user':
                $this->mainController->editUser();
                break;
            case 'update_user':
                $this->dbController->updateUser(filter_input(INPUT_POST, 'userid'));
                $this->mainController->admin();
                break;
            case 'reset_password':
                $this->mainController->editPassword();
                break;
            case 'submit_password':
                $this->dbController->changeUserPassword(filter_input(INPUT_POST, 'userid'));
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
                $this->dbController->addOwnerProfile(filter_input(INPUT_GET, 'userid'));
                $this->mainController->admin();
                break;
            case 'coffeeshop_owners':
                $this->mainController->coffeeshopOwnersSetup();
                break;
            case 'update_coffeeshop_owner':
                $csid = filter_input(INPUT_GET, 'coffeeshopid');
                $ownerId = filter_input(INPUT_GET, 'owner_select');

                if ($ownerId == -1){
                    $ownerId = null;
                }

                $this->dbController->setOwnerOfCoffeeshop($csid, $ownerId);
                $this->mainController->coffeeshopOwnersSetup();
                break;
            default:
                $this->mainController->admin();
        }
    }

}
