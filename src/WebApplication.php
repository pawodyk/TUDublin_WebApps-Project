<?php

namespace TUDublin;


class WebApplication {



    private $mainControl;

    public function __construct()
    {
        $this->mainControl = new MainController();
    }

    public function run() {
        $page = filter_input(INPUT_GET, 'page');


        switch($page){
            case 'login':
                $data_in = $_POST;
                var_dump($_POST['username']);
                var_dump($_POST['password']);

//                print filter_input(INPUT_POST, usernae);
//                print filter_input(INPUT_POST, password);
            case 'home':
            default:
                $this->mainControl->home();
                break;
        }

    }

}
