<?php

namespace TUDublin;


class WebApplication {

    private $loginController;

    public function __construct()
    {
        $this->mainController = new MainController();
        $this->loginController = new LoginController();

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
            case 'home':
            default:
                $this->mainController->home();
                break;
        }

    }

}
