<?php

namespace TUDublin;

class MainController extends Controller
{

    const TEMPLATES_PATH = __DIR__ . "/../templates/";

    private $twig;
    private $dbController;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(self::TEMPLATES_PATH);
        $this->twig = new \Twig\Environment($loader);
        $this->twig->addGlobal('session', $_SESSION);

        $this->dbController = new DatabaseController();

    }

    private function renderPage($template, $args)
    {

        if (isset($_SESSION['messages'])) {
            $args['messages'] = $_SESSION['messages'];
            $_SESSION['messages'] = [];
        }

        print $this->twig->render($template, $args);
    }

    public function home()
    {
        $coffeeshops = $this->dbController->getCoffeeshops();
        $reviews = $this->dbController->getAllReviews();

        $template = 'home.html.twig';
        $args = [
            'coffeeshop_list' => $coffeeshops,
            'reviews_list' => $reviews,
        ];

        $this->renderPage($template, $args);

    }

    public function shop()
    {
        $csid = filter_input(INPUT_GET, 'csid');

        $cs = $this->dbController->getCoffeeshop($csid);
        $reviews = $this->dbController->getAllReviewsFor($csid);
        $comments = $this->dbController->getPublishedCommentsFor($csid);

        $template = 'coffeeshop.html.twig';
        $args = [
            'coffeeshop' => $cs,
            'reviews' => $reviews,
            'comments' => $comments,
        ];

        $this->renderPage($template, $args);
    }

    public function shops()
    {
        $coffeeshops = $this->dbController->getCoffeeshops();

        $template = 'coffeeshoplist.html.twig';
        $args = [
            'coffeeshop_list' => $coffeeshops,
        ];

        $this->renderPage($template, $args);
    }

    public function newReview()
    {
        $cs = $this->dbController->getCoffeeshops();

        $template = 'writereview.html.twig';
        $args = [
            'coffeeshops' => $cs,
        ];

        $this->renderPage($template, $args);
    }

    public function reviewComments()
    {
        $comments = $this->dbController->getNewComments();

        $template = 'newcomments.html.twig';
        $args = [
            'comments' => $comments,
        ];

        $this->renderPage($template, $args);
    }

    public function viewReview()
    {
        $reviewId = filter_input(INPUT_GET, 'reviewId');

        $review = $this->dbController->getReview($reviewId);

        $template = 'reviewpage.html.twig';
        $args = [
            'review' => $review,
        ];

        $this->renderPage($template, $args);
    }

    public function admin()
    {
        $users = $this->dbController->getAllUsers();
        $owners = $this->dbController->getAllOwners();

        $template = 'admin.html.twig';
        $args = [
            'users' => $users,
            'owners' => $owners,
        ];

        $this->renderPage($template, $args);
    }

    public function editUser()
    {
        $userId = filter_input(INPUT_GET, 'userid');

        $user = $this->dbController->getUser($userId);

        $template = 'admin_edituser.html.twig';
        $args = [
            'user' => $user,
        ];

        $this->renderPage($template, $args);
    }

    public function editPassword()
    {
        $userid = filter_input(INPUT_GET, 'userid');

        $user = $this->dbController->getUser($userid);

        $template = 'admin_resetpassword.html.twig';
        $args = [
            'user' => $user,
        ];

        $this->renderPage($template, $args);
    }

    public function newUser()
    {
        $template = 'admin_newuser.html.twig';
        $args = [

        ];

        $this->renderPage($template, $args);
    }

    public function coffeeshopOwnersSetup()
    {
        $cs = $this->dbController->getCoffeeshops();
        $owners = $this->dbController->getAllOwners();

        $template = 'admin_coffeeshopsowners.html.twig';
        $args = [
            'coffeeshops' => $cs,
            'owners' => $owners,
        ];
        $this->renderPage($template, $args);
    }

    public function accessDenied()
    {
        $template = 'accessdenied.html.twig';
        $args = [

        ];
        $this->renderPage($template, $args);
    }

    public function editCoffeeshop()
    {
        $csid = filter_input(INPUT_GET, 'csid');

        $cs = $this->dbController->getCoffeeshop($csid);
        $cs_mi = $this->dbController->getMenuItems($cs->getId());
        $owners_mi = $this->dbController->getMenuItemsForOwner($cs->getOwnerId());


        $template = 'ownerscoffeeshops.html.twig';
        $args = [
            'coffeeshop'=> $cs,
            'menuitems'=> $cs_mi,
            'ownersitems'=>$owners_mi,
        ];

        $this->renderPage($template, $args);
    }

    public function editMenu()
    {
        $csid = filter_input(INPUT_GET, 'csid');
        $cs = $this->dbController->getCoffeeshop($csid);
        $cs_mi = $this->dbController->getMenuItems($cs->getId());
        $owners_mi = $this->dbController->getMenuItemsForOwner($cs->getOwnerId());


        $template = 'ownerscoffeeshops.html.twig';
        $args = [
            'coffeeshop'=> $cs,
            'menuitems'=> $cs_mi,
            'ownersitems'=>$owners_mi,
        ];

        $this->renderPage($template, $args);
    }

}
