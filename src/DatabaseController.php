<?php


namespace TUDublin;

require_once __DIR__ . '\..\env\dbConstants.php';

use TUDublin\dbObjects\{
    Coffeeshop,
    CoffeeshopRepository,
    CoffeeshopAddress,
    CoffeeshopAddressRepository,
    CoffeeshopComment,
    CoffeeshopCommentRepository,
    CoffeeshopOwner,
    CoffeeshopOwnerRepository,
    CoffeeshopReview,
    CoffeeshopReviewRepository,
    MenuItem,
    MenuItemRepository,
    Picture,
    PictureRepository,
    User,
    UserRepository
};

class DatabaseController
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
        $this->csRepo = new CoffeeshopRepository();
        $this->csAddressRepo = new CoffeeshopAddressRepository();
        $this->csCommentRepo = new CoffeeshopCommentRepository();
        $this->csOwnerRepo = new CoffeeshopOwnerRepository();
        $this->csReviewRepo = new CoffeeshopReviewRepository();
        $this->menuItemRepo = new MenuItemRepository();
        $this->pictureRepo = new PictureRepository();
        $this->userRepo = new UserRepository();
    }


    public function getCoffeeshops()
    {
        return $this->csRepo->findAll();
    }

    public function getCoffeeshop($coffeeshopId)
    {
        return $this->csRepo->find($coffeeshopId);
    }

    public function getAllReviews()
    {
        return $this->csReviewRepo->findAll();
    }

    public function getAllReviewsFor($coffeeshopId)
    {
        return $this->csReviewRepo->getAllReviewsForCoffeeshop($coffeeshopId);
    }

    public function getAllCommentFor($coffeeshopId){
        return $this->csCommentRepo->getAllCommentsForCoffeeshop($coffeeshopId);
    }

    /**
     * @param User $user
     */
    public function addUser()
    {
        $uname = filter_input(INPUT_POST, 'username');
        $pass = filter_input(INPUT_POST, 'password');
        $role = filter_input(INPUT_POST, 'role');

        $u = new User();
        $u->setUsername($uname);
        $u->setPassword($pass);
        $u->setUserType($role);
        if ($this->hasUniqueUsername()) {
            $this->userRepo->create($u);
        }else {
            $error[] = 'username already in use';
        }

    }

    public function hasUniqueUsername($username){
        $user = $this->userRepo->getUser($username);
        if ($user){
            return false;
        } else {
            return true;
        }

    }

    public function changeUserRole($userId){
        $u = $this->userRepo->find($userId);

        if ($u){
            $newRole = filter_input(INPUT_POST, 'user_role');
            $u->setUserType($newRole);
            $this->userRepo->update($u);
        } else {
            $error[] = 'could not find user';
        }

    }

    public function changeUserPassword($userId){
        $u = $this->userRepo->find($userId);

        if ($u){
            $newPass = filter_input(INPUT_POST, 'user_pass');
            $u->setUserType($newPass);
            $this->userRepo->update($u);
        } else {
            $error[] = 'could not find user';
        }
    }

    public function joinOwnerWithUser($ownerId, $userId){
        $owner = $this->csOwnerRepo->find($ownerId);
        $owner->setUserId($userId);
        $this->csOwnerRepo->update($owner);

    }

    public function setOwnerOfCoffeeshop($coffeeshopId, $ownerId){
        $cs = $this->csRepo->find($coffeeshopId);
        $cs->setOwnerId($ownerId);
        $this->csRepo->update($cs);
    }

    public function deleteUser($userId){
        $u = $this->userRepo->find($userId);

        if ($u->getUserType() == 'ROLE_SHOP'){
            $o = $this->csOwnerRepo->getOwnerByUserId($userId);
            $this->csOwnerRepo->delete($o->getId());
        }
        $this->userRepo->delete($userId);

    }

    public function setOwnerWithCoffeeshop($coffeeshopId, $ownerId){
        $cs = $this->csRepo->find($coffeeshopId);
        $cs->setOwnerId($ownerId);
        $this->csRepo->update($cs);
    }

    public function approveComment($commentId){
        $csCom = $this->csCommentRepo->find($commentId);
        $csCom->setIsPublished(true);
        $this->csCommentRepo->update($csCom);
    }

    public function deleteComment($commentId){
        $this->csCommentRepo->delete($commentId);
    }

    public function addCoffeeshop (){
        $cs = new Coffeeshop();
        $cs->setName(filter_input(INPUT_POST, 'coffeeshop_name'));
        $this->csRepo->create($cs);
    }

    public function addReview($coffeeshopId){
        $review = new CoffeeshopReview();
        $review->setCoffeeshopId($coffeeshopId);
        $review->setTitle(filter_input(INPUT_POST, 'review_title'));
        $review->setReview(filter_input(INPUT_POST, 'review_text'));
        $review->setRating(filter_input(INPUT_POST, 'review_rating'));
        $review->setTitle(filter_input(INPUT_POST, 'review_expense'));
        $review->setReviewDate(date('Y-m-d'));

        $this->csReviewRepo->create($review);
    }

}