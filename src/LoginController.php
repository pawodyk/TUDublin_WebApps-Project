<?php


namespace TUDublin;


use TUDublin\dbObjects\CoffeeshopOwnerRepository;
use TUDublin\dbObjects\UserRepository;

class LoginController extends Controller
{
    private $usersRepo;
    private $ownersRepo;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->usersRepo = new UserRepository();
        $this->ownersRepo = new CoffeeshopOwnerRepository();
    }

    /**
     *
     */
    public function login(){

        $uname = filter_input(INPUT_POST, 'username');
        $pass = filter_input(INPUT_POST, 'password');

        $user = $this->usersRepo->getUser($uname);

        if ($user){
            $databasePassword = $user->getPassword();
            if (password_verify($pass, $databasePassword)){
                $userId = $user->getId();
                $userRole = $user->getUserRole();

                $_SESSION['user_id'] = $userId;
                $_SESSION['user_role'] = $userRole;

                if ($userRole == 'ROLE_SHOP'){
                    $o = $this->ownersRepo->getOwnerByUserId($userId);
                    if ($o){
                        $_SESSION['owner_id'] = $o->getId();
                    }else {
                        $_SESSION = [];
                        $this->logError('You are not assigned to Owner account, please contact CSR Admin');
                        $this->redirect($_SERVER['HTTP_REFERER']);
                    }

                }

                $this->logMessage('Login Successful!');
                $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }

        $this->logError('Unable to login');
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function logout(){
        $_SESSION = [];
        $this->logMessage('You were successfully logged out');
        $this->redirect('/');
        die;
    }

    public function isLoggedIn(){
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])){
            return true;
        }
        return false;

    }

    public function veryfyUser($userId){
        if (isset($_SESSION['user_id'])){
            return $_SESSION['user_id'] == $userId;
        }
        return -1;
    }

    /**
     * @param $role
     */
    public function verifyAccess($role){

        if (isset($_SESSION['user_role'])){
            if($_SESSION['user_role'] == $role){
                return true;
            }
        }
        return false;
    }


}