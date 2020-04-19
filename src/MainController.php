<?php

namespace TUDublin;


class MainController {

    private $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    private function renderPage($template, $args){

        print $this->twig->render($template, $args);
    }

    public function home(){
        $template = 'home.html.twig';
        $args = [
            'user_role'=>''
        ];

        $this->renderPage($template, $args);

    }

}
