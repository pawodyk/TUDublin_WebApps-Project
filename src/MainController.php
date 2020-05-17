<?php

namespace TUDublin;

class MainController extends Controller
{
    public function homePage()
    {
        $coffeeshops = []; // $this->dbController->getCoffeeshops();
        $reviews = [] ; //$this->dbController->getAllReviews();

        $template = 'home.html.twig';
        $args = [
            'coffeeshop_list' => $coffeeshops,
            'reviews_list' => $reviews,
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
