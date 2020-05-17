<?php


namespace TUDublin;

require_once __DIR__ . '/../env/dbConstants.php';

class Controller
{

    private const TEMPLATES_PATH = __DIR__ . "/../templates";

    private $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(self::TEMPLATES_PATH);
        $this->twig = new \Twig\Environment($loader);
        $this->twig->addGlobal('session', $_SESSION);
    }

    protected function renderPage($template, $args)
    {

        if (isset($_SESSION['messages'])) {
            $args['messages'] = $_SESSION['messages'];
            $_SESSION['messages'] = [];
        }

        print $this->twig->render($template, $args);
    }

    protected function logError(string $message){
        $_SESSION['messages'][] = [ 'type'=>'error','text' =>$message, ];
    }

    protected function logMessage(string $message){
        $_SESSION['messages'][] = [ 'type'=>'success','text' =>$message, ];
    }

    protected function redirect(string $url, $args = []){

        if ($args){
            $url .= '?';
            foreach ($args as $var => $value){
                $url .= $var . '=' . $value . '&';
            }
            $url = rtrim($url, '&');
        }

        header('Location: ' . $url );
        die;
    }

    protected function debug($variable){
        print '<pre>';
        var_dump($variable);
//        die;
        print '</pre>';
    }
}