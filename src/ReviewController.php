<?php


namespace TUDublin;


use TUDublin\dbObjects\Coffeeshop;
use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\CoffeeshopReview;
use TUDublin\dbObjects\CoffeeshopReviewRepository;


class ReviewController extends Controller
{

    private $csRepo;
    private $csReviewRepo;


    public function __construct()
    {
        parent::__construct();

        $this->csRepo = new CoffeeshopRepository();
        $this->csReviewRepo = new CoffeeshopReviewRepository();

    }

    public function newReviewPage()
    {
        $cs = $this->csRepo->findAll();

        $template = 'writereview.html.twig';
        $args = [
            'coffeeshops' => $cs,
        ];

        $this->renderPage($template, $args);
    }

    public function reviewPage()
    {
        $reviewId = filter_input(INPUT_GET, 'reviewId');

        $review = $this->csReviewRepo->find($reviewId);

        $template = 'reviewpage.html.twig';
        $args = [
            'review' => $review,
        ];

        $this->renderPage($template, $args);
    }

    public function reviewsListPage()
    {
        $reviews = $this->csReviewRepo->findAll();

        $template = 'reviewslist.html.twig';
        $args = [
            'reviews_list' => $reviews,
        ];

        $this->renderPage($template, $args);
    }

    public function addReview()
    {
        $csid = filter_input(INPUT_POST, 'coffeeshopid');
        $title = filter_input(INPUT_POST, 'reviewtitle');
        $text = filter_input(INPUT_POST, 'reviewtext');
        $rating = filter_input(INPUT_POST, 'reviewrating', FILTER_VALIDATE_INT);
        $expense = filter_input(INPUT_POST, 'reviewexpense', FILTER_VALIDATE_INT);

        if (!isset($csid)) {
            $csname = filter_input(INPUT_POST, 'newcoffeeshopname');

            $cs = new Coffeeshop();
            if (strlen($csname) > 120) {
                $csid = 0;
                $this->logError("Coffeeshop name cannot be empty or have more then 120 characters.");
            } else {
                $cs->setName($csname);
                $csid = $this->csRepo->create($cs);
            }


        }
        if ($csid <= 0) {
            $this->logError("Failed to create new Coffee Shop");

        } elseif (strlen($title) > 120) {
            $this->logError('Review Title is too long');

        } else {
            $review = new CoffeeshopReview();

            $review->setCoffeeshopId($csid);
            $review->setTitle($title);
            $review->setReview($text);
            $review->setRating($rating);
            $review->setExpense($expense);
            $review->setReviewDate(date('Y-m-d'));

            $result = $this->csReviewRepo->create($review);

            if ($result > 0) {
                $this->logMessage('Successfully added new review');
                $this->redirect('/', [
                    'page' => 'shop',
                    'csid' => $csid,
                ]);
            }
        }

        $this->logError('Could not add the review');
        $this->redirect('/', [
            'page' => 'new_review'
        ]);


    }
}