<?php


namespace TUDublin;


use TUDublin\dbObjects\Coffeeshop;
use TUDublin\dbObjects\CoffeeshopAddressRepository;
use TUDublin\dbObjects\CoffeeshopCommentRepository;
use TUDublin\dbObjects\CoffeeshopOwnerRepository;
use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\CoffeeshopReview;
use TUDublin\dbObjects\CoffeeshopReviewRepository;
use TUDublin\dbObjects\MenuItemRepository;
use TUDublin\dbObjects\PictureRepository;
use TUDublin\dbObjects\UserRepository;

class ReviewController extends Controller
{

    private $csRepo;
    private $csAddressRepo;
    private $csCommentRepo;
    private $csOwnerRepo;
    private $csReviewRepo;
    private $menuItemRepo;
    private $pictureRepo;
    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->csRepo = new CoffeeshopRepository();
//        $this->csAddressRepo = new CoffeeshopAddressRepository();
//        $this->csCommentRepo = new CoffeeshopCommentRepository();
//        $this->csOwnerRepo = new CoffeeshopOwnerRepository();
        $this->csReviewRepo = new CoffeeshopReviewRepository();
//        $this->menuItemRepo = new MenuItemRepository();
//        $this->pictureRepo = new PictureRepository();
//        $this->userRepo = new UserRepository();
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

    public function addReview()
    {
        $csid = filter_input(INPUT_POST, 'coffeeshopid');
        $title = filter_input(INPUT_POST, 'reviewtitle');
        $text = filter_input(INPUT_POST, 'reviewtext');
        $rating = filter_input(INPUT_POST, 'reviewrating', FILTER_VALIDATE_INT);
        $expense = filter_input(INPUT_POST, 'reviewexpense', FILTER_VALIDATE_INT);

        if (!isset($csid)){
            $csname = filter_input(INPUT_POST, 'newcoffeeshopname');

            $cs = new Coffeeshop();
            $cs->setName($csname);
            $csid = $this->csRepo->create($cs);

            if ($csid < 0){
                $this->logError("Failed to create new Coffee Shop");
                $this->redirect('/', [
                    'page'=>'new_review'
                ]);
            }
        }

        $review = new CoffeeshopReview();

        $review->setCoffeeshopId($csid);
        $review->setTitle($title);
        $review->setReview($text);
        $review->setRating($rating);
        $review->setExpense($expense);
        $review->setReviewDate(date('Y-m-d'));


        $result = $this->csReviewRepo->create($review);

        if ($result > 0 ){
            $this->logMessage('Successfully added new review');
            $this->redirect('/', [
                'page'=>'shop',
                'csid'=>$csid,
            ]);
        } else {
            $this->logError('Could not add the review');
            $this->redirect('/', [
                'page'=>'new_review'
            ]);
        }
    }

    /* DEPRACATED FUNCTIONS TODO remove after cleanup */

    /**
     * @param $reviewId
     * @return mixed|null
     * @deprecated
     */
    public function getReview($reviewId){
        return $this->csReviewRepo->find($reviewId);
    }

    /**
     * @return array
     * @deprecated
     */
    public function getAllReviews()
    {
        return $this->csReviewRepo->findAll();
    }

    /**
     * @param $coffeeshopId
     * @return array
     * @deprecated
     */
    public function getAllReviewsFor($coffeeshopId)
    {
        return $this->csReviewRepo->getAllReviewsForCoffeeshop($coffeeshopId);
    }
}