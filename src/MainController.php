<?php

namespace TUDublin;

use TUDublin\DatabaseController;

class MainController {

    private $twig;
    private $dbController;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->dbController = new DatabaseController();
    }

    private function renderPage($template, $args){

        print $this->twig->render($template, $args);
    }

    public function home(){
        $template = 'home.html.twig';
        $args = [
            'user_role'=>'ROLE_SHOP',
            'user_id'=>1,
            'is_loggedIn'=> true,
            'coffeeshop_list'=>$this->dbController->getCoffeeshops(),
        ];
//        print '<pre>';
//        var_dump($this->dbController->getCoffeeshops());
//        die;

        $this->dbController->getCoffeeshops();
        $this->renderPage($template, $args);

    }

}
