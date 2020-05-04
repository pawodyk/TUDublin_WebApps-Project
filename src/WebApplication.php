<?php

namespace TUDublin;


class WebApplication {

    private $loginController;
    private $mainController;
    private $dbController;

    public function __construct()
    {
        $this->mainController = new MainController();
        $this->loginController = new LoginController();
        $this->dbController = new DatabaseController();

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
            default:
                $this->mainController->admin();
        }
    }

}
