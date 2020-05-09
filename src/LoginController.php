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
                $_POST = [];

                //TODO check ownerid and add it to session

                $this->logMessage('Login Successful!');

                header('Location: /' );
                die;
            }
        }

        $this->logError('Unable to login');
        (new MainController())->home();
    }

    public function logout(){
        $_SESSION = [];
        header('Location: /' );
        die;
    }

    public function veryfyUser(){
        if (isset($_SESSION['user_id'])){

        }
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