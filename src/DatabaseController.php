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

class DatabaseController extends Controller
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

    public function getAllCoffeeshopsForOwner($ownerId)
    {
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

    public function getAllOwners()
    {
        return $this->csOwnerRepo->findAll();
    }

    /**
     * @param User $user
     */
    public function addUser()
    {
        $username = filter_input(INPUT_POST, 'username');
        $userpass = filter_input(INPUT_POST, 'userpass');
        $userrole = filter_input(INPUT_POST, 'userrole');

        $isValid = true;

        if (empty($username)) {
            $this->logError("name is empty");
            $isValid = false;
        } else {
            if (!$this->hasUniqueUsername($username)) {
                $isValid = false;
                $this->logError('username already in use');
            } elseif (strlen($username) > 50) {
                $this->logError('username is too long');
            }
        }

        if (empty($userpass)) {
            $this->logError("password is empty");
            $isValid = false;
        }

        if ($isValid) {
            $u = new User();
            $u->setUsername($username);
            $u->setPassword($userpass);
            $u->setUserRole($userrole);

            $returncode = $this->userRepo->create($u);

            if ($returncode > 0) {
                $this->logMessage('User added successfully with id ' . $returncode);
            } else {
                $this->logError('could not add the user');
            }
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

    public function updateUser()
    {
        $userId = filter_input(INPUT_POST, 'userid');
        $newName = filter_input(INPUT_POST, 'username');
        $newRole = filter_input(INPUT_POST, 'userrole');

        $u = $this->userRepo->find($userId);

        if ($u) {
            if ($newName != $u->getUsername()) {
                if ($this->hasUniqueUsername($newName)) {
                    $u->setUsername($newName);
                } else {
                    $this->logError('username already in use');
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


            $result = $this->userRepo->update($u);

            if ($result) {
                $this->logMessage('User ID ' . $userId . ' updated successfully!');
                return;
            } else {
                $this->logError('Could not Update the User ' . $userId);
            }

        } else {
            $this->logError('could not find user');
        }
    }

    public function changeUserPassword()
    {
        $userId = filter_input(INPUT_POST, 'userid');
        $newPass = filter_input(INPUT_POST, 'userpass');

        $u = $this->userRepo->find($userId);

        if ($u) {
            $u->setPassword($newPass);
            $result = $this->userRepo->update($u);

            if ($result) {
                $this->logMessage('Password for user ' . $userId . ' changed successfully!');
                return;
            } else {
                $this->logError('Could not Change password for user ' . $userId);
            }

        } else {
            $this->logError('could not find user');
        }
    }

    public function addOwnerProfile()
    {
        $userId = filter_input(INPUT_GET, 'userid');

        $owner = new CoffeeshopOwner();
        $owner->setUserId($userId);
        $this->csOwnerRepo->create($owner);
    }

    public function setOwnerOfCoffeeshop()
    {
        $coffeeshopId = filter_input(INPUT_GET, 'coffeeshopid');
        $ownerId = filter_input(INPUT_GET, 'owner_select');

        if ($ownerId == -1) {
            $ownerId = null;
        }

        $cs = $this->csRepo->find($coffeeshopId);
        $cs->setOwnerId($ownerId);
        $result = $this->csRepo->update($cs);

        if ($result) {
            $this->logMessage('Coffeeshop ID ' . $coffeeshopId . ' asigned to owner ' . $ownerId);
            return;
        } else {
            $this->logError('Could not assign Coffeeshop ID ' . $coffeeshopId . ' to owner ' . $ownerId);
        }
    }

    public function deleteUser()
    {
        $userId = filter_input(INPUT_GET, 'userid');

        $u = $this->userRepo->find($userId);

        if ($u->getUserRole() == 'ROLE_SHOP') {
            $this->eraseOwnerData($userId);
        }

        $result = $this->userRepo->delete($userId);
        if ($result) {
            $this->logMessage('User ID ' . $userId . ' deleted successfully!');
            return;
        } else {
            $this->logError('Could not delete the User ' . $userId);
        }

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

    public function addCoffeeshop($coffeeshopName)
    {
        $cs = new Coffeeshop();
        $cs->setName($coffeeshopName);
        $this->csRepo->create($cs);
    }

    public function addReview()
    {
        $review = new CoffeeshopReview();

        $csid = filter_input(INPUT_POST, 'coffeeshopid');

        $review->setCoffeeshopId($csid);
        $review->setTitle(filter_input(INPUT_POST, 'reviewtitle'));
        $review->setReview(filter_input(INPUT_POST, 'reviewtext'));
        $review->setRating(filter_input(INPUT_POST, 'reviewrating'));
        $review->setTitle(filter_input(INPUT_POST, 'reviewexpense'));
        $review->setReviewDate(date('Y-m-d'));

        $this->csReviewRepo->create($review);
    }
}