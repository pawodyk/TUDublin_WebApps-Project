<?php

namespace TUDublin;


class WebApplication {

    const TEMPLATES_PATH = __DIR__ . "/../templates/";

    private $mainControl;

    public function __construct()
    {
        $loader =  new \Twig\Loader\FilesystemLoader(self::TEMPLATES_PATH);
        $twig = new \Twig\Environment($loader);
        $this->mainControl = new MainController($twig);
    }

    public function run() {
        $page = filter_input(INPUT_GET, 'page');


        switch($page){
            case 'home':
            default:
                $this->mainControl->home();
                break;
        }

    }

}
