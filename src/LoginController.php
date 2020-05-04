<?php


namespace TUDublin;


use TUDublin\dbObjects\UserRepository;

class LoginController
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

//        var_dump($user);die;

        if ($user){
            $databasePassword = $user->getPassword();
            if (password_verify($pass, $databasePassword)){
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_role'] = $user->getUserType(); //TODO change user_type to user_role for consistency
                $_POST = [];

                //TODO check ownerid and add it to session

                (new MainController())->home();
            }
        }
        print "unable to login";

    }

    public function logout(){
        $_SESSION = [];
        (new MainController())->home();
    }

    public function veryfyUser(){
        if (isset($_SESSION['user_id'])){

        }
    }

    /**
     * @param $role
     */
    public function verifyAccess($role){
        if (isset($_SESSION['role'])){

        }
    }


}