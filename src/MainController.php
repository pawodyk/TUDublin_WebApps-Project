<?php

namespace TUDublin;

class MainController extends Controller
{

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

        if (isset($_SESSION['messages'])){
            $args['messages'] = $_SESSION['messages'];
            $_SESSION['messages'] = [];
        }

        print $this->twig->render($template, $args);
    }

    public function home(){
        $template = 'home.html.twig';
        $args = [
            'coffeeshop_list'=>$this->dbController->getCoffeeshops(),
            'reviews_list'=> $this->dbController->getAllReviews(),
        ];

        $this->renderPage($template, $args);

    }

    public function shop(){
        $csid = filter_input(INPUT_GET, 'csid');

        $template = 'coffeeshop.html.twig';
        $args = [
            'coffeeshop'=>$this->dbController->getCoffeeshop($csid),
            'reviews'=>$this->dbController->getAllReviewsFor($csid),
            'comments'=>$this->dbController->getAllCommentFor($csid),
        ];

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
            'owners' => $this->dbController->getAllOwners(),
        ];

        $this->renderPage($template, $args);
    }

    public function editUser(){
        $userId = filter_input(INPUT_GET, 'userid');

        $template = 'admin_edituser.html.twig';
        $args = [
            'user' => $this->dbController->getUser($userId),
        ];
//        print '<pre>';
//        var_dump($args);die;

        $this->renderPage($template, $args);
    }

    public function editPassword(){
        $userid = filter_input(INPUT_GET, 'userid');

        $template = 'admin_resetpassword.html.twig';
        $args = [
            'user' => $this->dbController->getUser($userid),
        ];

        $this->renderPage($template, $args);
    }

    public function newUser(){
        $template = 'admin_newuser.html.twig';
        $args = [

        ];

        $this->renderPage($template, $args);
    }

    public function coffeeshopOwnersSetup(){
        $template = 'admin_coffeeshopsowners.html.twig';
        $args = [
          'coffeeshops' => $this->dbController->getCoffeeshops(),
            'owners' => $this->dbController->getAllOwners(),
        ];
        $this->renderPage($template, $args);
    }

    public function accessDenied(){
        $template = 'accessdenied.html.twig';
        $args = [

        ];
        $this->renderPage($template, $args);
    }

    public function test(){
        $testValue = [];

        $testValue[] = [];

        print '<pre>';
        print_r($testValue);die;
    }



}
