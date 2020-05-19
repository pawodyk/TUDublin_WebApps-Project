<?php


namespace TUDublin;


use TUDublin\dbObjects\CoffeeshopOwner;
use TUDublin\dbObjects\CoffeeshopOwnerRepository;
use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\MenuItemRepository;
use TUDublin\dbObjects\User;
use TUDublin\dbObjects\UserRepository;

class AdminController extends Controller
{

    private $csRepo;
    private $csOwnerRepo;
    private $menuItemRepo;
    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->csRepo = new CoffeeshopRepository();
        $this->csOwnerRepo = new CoffeeshopOwnerRepository();
        $this->menuItemRepo = new MenuItemRepository();
        $this->userRepo = new UserRepository();
    }

    public function adminPage()
    {
        $users = $this->userRepo->findAll();
        $owners = $this->csOwnerRepo->findAll();

        $template = 'admin.html.twig';
        $args = [
            'users' => $users,
            'owners' => $owners,
        ];

        $this->renderPage($template, $args);
    }

    public function editUserPage()
    {
        $userId = filter_input(INPUT_GET, 'userid');

        $user = $this->userRepo->find($userId);

        $template = 'admin_edituser.html.twig';
        $args = [
            'user' => $user,
        ];

        $this->renderPage($template, $args);
    }

    public function editPasswordPage()
    {
        $userid = filter_input(INPUT_GET, 'userid');

        $user = $this->userRepo->find($userid);

        $template = 'admin_resetpassword.html.twig';
        $args = [
            'user' => $user,
        ];

        $this->renderPage($template, $args);
    }

    public function newUserPage()
    {
        $template = 'admin_newuser.html.twig';
        $args = [

        ];

        $this->renderPage($template, $args);
    }

    public function coffeeshopOwnersSetupPage()
    {
        $cs = $this->csRepo->findAll();
        $owners = $this->csOwnerRepo->findAll();

        $template = 'admin_coffeeshopsowners.html.twig';
        $args = [
            'coffeeshops' => $cs,
            'owners' => $owners,
        ];
        $this->renderPage($template, $args);
    }

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

    /* helper functions */

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

    private function createOwnerProfileFor($userId){
        $owner = new CoffeeshopOwner();
        $owner->setUserId($userId);
        $this->csOwnerRepo->create($owner);
    }

}