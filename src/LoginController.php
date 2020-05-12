<?php


namespace TUDublin;


use TUDublin\dbObjects\UserRepository;

class LoginController extends Controller
{
    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->usersRepo = new UserRepository();
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
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_role'] = $user->getUserRole();

                //TODO check ownerid and add it to session

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