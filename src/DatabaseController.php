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

    public function getAllCoffeeshopsForOwner($ownerId){
        return $this->csRepo->getAllCoffeeshopsFor($ownerId);
    }

    public function getAllReviews()
    {
        return $this->csReviewRepo->findAll();
    }

    public function getAllReviewsFor($coffeeshopId)
    {
        return $this->csReviewRepo->getAllReviewsForCoffeeshop($coffeeshopId);
    }

    public function getAllCommentFor($coffeeshopId)
    {
        return $this->csCommentRepo->getAllCommentsForCoffeeshop($coffeeshopId);
    }

    public function getAllUsers()
    {
        return $this->userRepo->findAll();
    }

    public function getUser($userId)
    {
        return $this->userRepo->find($userId);
    }

    public function getAllOwners(){
        return $this->csOwnerRepo->findAll();
    }

    /**
     * @param User $user
     */
    public function addUser()
    {
        global $errors;
        $isValid = true;

        $uname = filter_input(INPUT_POST, 'username');
        $pass = filter_input(INPUT_POST, 'userpass');
        $role = filter_input(INPUT_POST, 'userrole');

        if (empty($uname)) {
            $errors[] = "name is empty";
            $isValid = false;
        } else {
            if (!$this->hasUniqueUsername($uname)) {
                $isValid = false;
                $errors[] = 'username already in use';
            } elseif (strlen($uname) > 50) {
                $errors[] = 'username is too long';
            }
        }

        if (empty($pass)) {
            $errors[] = "password is empty";
            $isValid = false;
        }

        if ($isValid) {
            $u = new User();
            $u->setUsername($uname);
            $u->setPassword($pass);
            $u->setUserRole($role);

            $this->userRepo->create($u);
        } else {
            //$errors[] = 'could not add the user' ;
        }

    }

    public function hasUniqueUsername($username)
    {
        $user = $this->userRepo->getUser($username);

        if ($user) {
            return false;
        } else {
            return true;
        }

    }

    public function updateUser($userId)
    {

        $u = $this->userRepo->find($userId);


        if ($u) {

            $newName = filter_input(INPUT_POST, 'username');
            $newRole = filter_input(INPUT_POST, 'userrole');

            if ($newName != $u->getUsername()) {
                if ($this->hasUniqueUsername($newName)) {
                    $u->setUsername($newName);
                } else {
                    $error[] = 'username already in use';
                }
            }

            if ($newRole != $u->getUserRole()) {
                if ($newRole == 'ROLE_SHOP') {

                    $o = new CoffeeshopOwner();
                    $o->setUserId($u->getId());
                    $this->csOwnerRepo->create($o);

                } elseif ($u->getUserRole() == 'ROLE_SHOP') {
                    $this->eraseOwnerData($u->getId());
                }
                $u->setUserRole($newRole);
            }


            $this->userRepo->update($u);

        } else {
            $errors[] = 'could not find user';
        }


    }

    public function changeUserPassword($userId)
    {
        $u = $this->userRepo->find($userId);

        if ($u) {
            $newPass = filter_input(INPUT_POST, 'userpass');
            $u->setPassword($newPass);
            $this->userRepo->update($u);

        } else {
            $errors[] = 'could not find user';
        }
    }

    public function addOwnerProfile($userId)
    {
        $owner = new CoffeeshopOwner();
        $owner->setUserId($userId);
        $this->csOwnerRepo->create($owner);
    }

    public function setOwnerOfCoffeeshop($coffeeshopId, $ownerId)
    {
        $cs = $this->csRepo->find($coffeeshopId);
        $cs->setOwnerId($ownerId);
        $this->csRepo->update($cs);
    }

    public function deleteUser($userId)
    {
        $u = $this->userRepo->find($userId);

        if ($u->getUserRole() == 'ROLE_SHOP') {
            $this->eraseOwnerData($userId);
        }

        $this->userRepo->delete($userId);

    }

    public function eraseOwnerData($userId)
    {
        $o = $this->csOwnerRepo->getOwnerByUserId($userId);
        if ($o) {
            $ownedCoffeeshops = $this->csRepo->getAllCoffeeshopsFor($o->getId());
            foreach ($ownedCoffeeshops as $cs) {
                $cs->setOwnerId(null);
                $cs->setSummary(null);

                $this->menuItemRepo->deleteAllMenusForCoffeeshop($cs->getMenuId());

                $cs->setMenuId(null);
                $this->csRepo->update($cs);
            }

            $this->csOwnerRepo->delete($o->getId());
        }
    }


    public function approveComment($commentId)
    {
        $csCom = $this->csCommentRepo->find($commentId);
        $csCom->setIsPublished(true);
        $this->csCommentRepo->update($csCom);
    }

    public function deleteComment($commentId)
    {
        $this->csCommentRepo->delete($commentId);
    }

    public function addCoffeeshop()
    {
        $cs = new Coffeeshop();
        $cs->setName(filter_input(INPUT_POST, 'coffeeshop_name'));
        $this->csRepo->create($cs);
    }

    public function addReview($coffeeshopId)
    {
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