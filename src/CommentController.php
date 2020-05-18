<?php


namespace TUDublin;


use TUDublin\dbObjects\CoffeeshopAddressRepository;
use TUDublin\dbObjects\CoffeeshopComment;
use TUDublin\dbObjects\CoffeeshopCommentRepository;
use TUDublin\dbObjects\CoffeeshopOwnerRepository;
use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\CoffeeshopReviewRepository;
use TUDublin\dbObjects\MenuItemRepository;
use TUDublin\dbObjects\PictureRepository;
use TUDublin\dbObjects\UserRepository;

class CommentController extends Controller
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
//        $this->csRepo = new CoffeeshopRepository();
//        $this->csAddressRepo = new CoffeeshopAddressRepository();
        $this->csCommentRepo = new CoffeeshopCommentRepository();
//        $this->csOwnerRepo = new CoffeeshopOwnerRepository();
//        $this->csReviewRepo = new CoffeeshopReviewRepository();
//        $this->menuItemRepo = new MenuItemRepository();
//        $this->pictureRepo = new PictureRepository();
//        $this->userRepo = new UserRepository();
    }

    public function commentsReviewPage()
    {
        $comments = $this->csCommentRepo->getAllNonPublishedComments();

        $template = 'newcomments.html.twig';
        $args = [
            'comments' => $comments,
        ];

        $this->renderPage($template, $args);
    }

    public function approveComment()
    {
        $commentId = filter_input(INPUT_GET, comment);

        $csCom = $this->csCommentRepo->find($commentId);
        $csCom->setIsPublished(true);
        $result = $this->csCommentRepo->update($csCom);

        if ($result){
            $this->logMessage('Comment ID ' . $commentId . ' was successfully approved and will now be visible on the Coffee Shop profile.');
        } else {
            $this->logError('could not approve comment ID ' . $commentId);
        }

        $this->redirect('/', [
            'page'=>'comments'
        ]);

    }

    public function deleteComment()
    {
        $commentId = filter_input(INPUT_GET, comment);

        $result = $this->csCommentRepo->delete($commentId);

        if ($result){
            $this->logMessage('Comment ID ' . $commentId . ' was deleted successfully');
        } else {
            $this->logError('could not delete comment ID ' . $commentId);
        }

        $this->redirect('/', [
            'page'=>'comments'
        ]);
    }

    public function addComment(){
        $validInput = true;

        $csid = filter_input(INPUT_POST, 'comment_csid');
        $message = filter_input(INPUT_POST, 'comment_message');
        $name = filter_input(INPUT_POST, 'comment_person_name');

        if (strlen($message) > 500){
            $validInput =false;
            $this->logError('Message is too long, Max 500 characters');
        }
        if (strlen($name) > 60) {
            $validInput =false;
            $this->logError('Used name is too long, max 60 characters.');
        }

        if ($validInput){
            $com = new CoffeeshopComment();

            $com->setCoffeeshopId($csid);
            $com->setMessage($message);
            $com->setName($name);

            $result = $this->csCommentRepo->create($com);
        } else {
            $result = 0;
        }


        if ($result>0){
            $this->logMessage("Comment added successfully and will be reviewed by member of CSR staff.");
        }else{
            $this->logError("Comment could not be added, please try later.");
        }

        $this->redirect('/', [
            'page'=>'shop',
            'csid'=>$csid,
        ]);

    }

    /* DEPRECATED FUNCTIONS TODO remove after cleanup */

    /**
     * @deprecated
     */
    public function getAllCommentFor($coffeeshopId)
    {
        return $this->csCommentRepo->getAllCommentsForCoffeeshop($coffeeshopId);
    }

    /**
     * @deprecated
     */
    public function  getPublishedCommentsFor($coffeeshopId){
        $comments = $this->csCommentRepo->getAllCommentsForCoffeeshop($coffeeshopId);
        foreach ($comments as $key=>$comment){
            if (!$comment->getIsPublished()){
                unset($comments[$key]);
            }
        }
        return $comments;

    }

    /**
     * @deprecated
     */
    public function getNewComments(){
        return $this->csCommentRepo->getAllNonPublishedComments();
    }

}