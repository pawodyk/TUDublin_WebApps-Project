<?php

namespace TUDublin;

class MainController extends Controller
{
    public function homePage()
    {
        $template = 'home.html.twig';
        $args = [

        ];

        $this->renderPage($template, $args);
    }

    public function accessDeniedPage()
    {
        $template = 'accessdenied.html.twig';
        $args = [

        ];
        $this->renderPage($template, $args);
    }

}
