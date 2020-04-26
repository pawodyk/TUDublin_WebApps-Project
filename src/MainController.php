<?php

namespace TUDublin;

use TUDublin\DatabaseController;

class MainController {

    const TEMPLATES_PATH = __DIR__ . "/../templates/";

    private $twig;
    private $dbController;

    public function __construct()
    {
        $loader =  new \Twig\Loader\FilesystemLoader(self::TEMPLATES_PATH);
        $this->twig = new \Twig\Environment($loader);
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
            'reviews_list'=> $this->dbController->getAllReviews(),
        ];
//        print '<pre>';
//        var_dump($this->dbController->getCoffeeshops());
//        die;

        $this->dbController->getCoffeeshops();
        $this->renderPage($template, $args);

    }

    public function shop(){

    }

}
