<?php

namespace TUDublin;

use TUDublin\DatabaseController;
use TUDublin\dbObjects\CoffeeshopComment;
use TUDublin\dbObjects\CoffeeshopCommentRepository;
use TUDublin\dbObjects\CoffeeshopRepository;

class MainController {

    const TEMPLATES_PATH = __DIR__ . "/../templates/";

    private $twig;
    private $dbController;

    public function __construct()
    {
        $loader =  new \Twig\Loader\FilesystemLoader(self::TEMPLATES_PATH);
        $this->twig = new \Twig\Environment($loader);
        $this->twig->addGlobal('session', $_SESSION);
        $this->dbController = new DatabaseController();

    }

    private function renderPage($template, $args){

        print $this->twig->render($template, $args);
    }

    public function home(){
        $template = 'home.html.twig';
        $args = [
            'coffeeshop_list'=>$this->dbController->getCoffeeshops(),
            'reviews_list'=> $this->dbController->getAllReviews(),
        ];

//        print '<pre>';
//        print_r($args);die;

        $this->renderPage($template, $args);

    }

    public function shop($csid){
        $template = 'coffeeshop.html.twig';
        $args = [
            'coffeeshop'=>$this->dbController->getCoffeeshop($csid),
            'reviews'=>$this->dbController->getAllReviewsFor($csid),
            'comments'=>[
                'hello world',
                'good comment',
            ],
        ];
//        print '<pre>';
//        print_r($args);die;

        $this->renderPage($template,$args);
    }

    public function shops(){
        $template= 'coffeeshoplist.html.twig';
        $args = [
            'coffeeshop_list'=>$this->dbController->getCoffeeshops(),
        ];

        $this->renderPage($template,$args);
    }

    public function admin(){
        $template = 'admin.html.twig';
        $args= [
            'users' => $this->dbController->getAllUsers(),
        ];

        $this->renderPage($template, $args);
    }

    public function editUser(){
        $template = 'admin_edituser.html.twig';
        $args = [
            'user' => $this->dbController->getUser(filter_input(INPUT_GET, 'userid')),
        ];

            $this->renderPage($template, $args);
    }

    public function test(){


        $csRepo = new CoffeeshopRepository();
        $testValue = [];
        $testValue[] = $csRepo->getTopCoffeeshops(5);

        $commentRepo = new CoffeeshopCommentRepository();
        $testValue[] = $commentRepo->getAllNonPublishedComments();

        print '<pre>';
        print_r($testValue);die;
    }



}
