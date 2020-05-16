<?php


namespace TUDublin;

require_once __DIR__ . '/../env/dbConstants.php';

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

    public function getNewComments(){
        return $this->csCommentRepo->getAllNonPublishedComments();
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

        if (!$this->validUsername($username)) {
            $isValid = false;
        }

        if (!$this->validPassword($userpass)) {
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
                if ($userrole == 'ROLE_SHOP'){
                    $this->createOwnerProfileFor($returncode);
                }
                $this->redirect('/', [
                    'page' => 'admin',
                ]);
            } else {
                $this->logError('could not add the user');
            }
        }
        $this->redirect('/', [
            'page' => 'admin',
            'action' => 'new_user',
        ]);
    }

    private function validUsername($username)
    {
        if (empty($username)) {
            $this->logError("username cannot be empty");
            return false;
        } else {
            if (strlen($username) > 50) {
                $this->logError('username is too long');
                return false;
            } elseif (!$this->hasUniqueUsername($username)) {
                $this->logError('username already in use');
                return false;
            }
        }

        return true;
    }

    private function hasUniqueUsername($username)
    {
        $user = $this->userRepo->getUser($username);

        if ($user) {
            return false;
        } else {
            return true;
        }

    }

    private function validPassword($password)
    {
        if (empty($password)) {
            $this->logError("password cannot be empty");
            return false;
        } else {
            $passlen = strlen($password);
            if ($passlen < 5) {
                $this->logError("password is too short.");
                return false;
            } elseif ($passlen > 100) {
                $this->logError("password is too long.");
                return false;
            }

            //TODO here can go some other password validity checks like at least 1 digit and 1 letter
        }

        return true;
    }

    public function updateUser()
    {
        $userId = filter_input(INPUT_POST, 'userid');
        $newName = filter_input(INPUT_POST, 'username');
        $newRole = filter_input(INPUT_POST, 'userrole');

        $u = $this->userRepo->find($userId);

        $hasChanged = false;

        if ($u) {
            // if there was a change
            if ($newName != $u->getUsername()) {
                // validate input
                if ($this->validUsername($newName)) {
                    $u->setUsername($newName);
                    $hasChanged = true;
                }
            }

            if ($newRole != $u->getUserRole()) {
                if ($newRole == 'ROLE_SHOP') {

                    $this->createOwnerProfileFor($u->getId());

                } elseif ($u->getUserRole() == 'ROLE_SHOP') {
                    $this->eraseOwnerData($u->getId());
                }
                $u->setUserRole($newRole);
                $hasChanged = true;
            }

            if ($hasChanged) {
                $result = $this->userRepo->update($u);
                if ($result) {
                    $this->logMessage('User ID ' . $userId . ' updated successfully!');
                    $this->redirect('/', [
                        'page' => 'admin'
                    ]);
                } else {
                    $this->logError('Could not Update the User ' . $userId);
                }
            }

            $this->redirect('/', [
                'page' => 'admin',
                'action' => 'edit_user',
                'userid' => $u->getId(),
            ]);
        } else {
            $this->logError('could not find user');
        }

        $this->redirect('/', [
            'page' => 'admin'
        ]);
    }

    public function changeUserPassword()
    {
        $userId = filter_input(INPUT_POST, 'userid');
        $newPass = filter_input(INPUT_POST, 'userpass');

        $u = $this->userRepo->find($userId);

        if ($u) {
            if ($this->validPassword($newPass)) {
                $u->setPassword($newPass);
                $result = $this->userRepo->update($u);

                if ($result) {
                    $this->logMessage('Password for user ' . $userId . ' changed successfully!');
                    $this->redirect('/', [
                        'page' => 'admin',
                    ]);
                } else {
                    $this->logError('Could not Change password for user ' . $userId);
                }
            }
            $this->redirect('/', [
                'page' => 'admin',
                'action' => 'reset_password',
                'userid' => $u->getId(),
            ]);

        } else {
            $this->logError('could not find user');
        }
        $this->redirect('/', [
            'page' => 'admin',
            'action' => 'new_user',
        ]);
    }

    public function addOwnerProfile()
    {
        $userId = filter_input(INPUT_GET, 'userid');

        $this->createOwnerProfileFor($userId);

        $this->redirect('/', [
            'page' => 'admin'
        ]);
    }

    private function createOwnerProfileFor($userId){
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
            if ($ownerId) {
                $this->logMessage('Owner ' . $ownerId . ' now owns the coffeeshop ID ' . $coffeeshopId);
            } else {
                $this->logMessage('Removed Owner from coffeeshop ID ' . $coffeeshopId);
            }

        } else {
            $this->logError('Could not assign Coffeeshop ID ' . $coffeeshopId . ' to owner ' . $ownerId);
        }
        header('Location: /?page=admin&action=coffeeshop_owners');
    }

    public function deleteUser()
    {
        $userId = filter_input(INPUT_GET, 'userid');

        $u = $this->userRepo->find($userId);

        if ($u) {
            if ($u->getUserRole() == 'ROLE_SHOP') {
                $this->eraseOwnerData($userId);
            }

            $result = $this->userRepo->delete($userId);
            if ($result) {
                $this->logMessage('User ID ' . $userId . ' deleted successfully!');
                $this->redirect('/', [
                    'page' => 'admin',

                ]);
            } else {
                $this->logError('Could not delete the User ' . $userId);
            }
        }

        $this->redirect('/', [
            'page' => 'admin',
            'action' => 'edit_user',
            'userid' => $userId,
        ]);

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
}