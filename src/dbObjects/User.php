<?php

namespace TUDublin\dbObjects;

class User {

    const CREATE_TABLE_SQL =
<<<HERE
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `user_role` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
);
HERE;

    private $id;
    private $username;
    private $password;
    private $user_role;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $username = str_replace(' ', '', $username);
        if(strlen($username) > 50){
            $this->username = substr($username, 0, 50);
        } else{
            $this->username = $username;
        }
        
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hashPassword;
    }

    /**
     * @return string
     */
    public function getUserRole()
    {
        return $this->user_role;
    }

    /**
     * @param string $user_role
     */
    public function setUserRole($user_role)
    {
        $allowed = ['ROLE_STAFF', 'ROLE_SHOP', 'ROLE_ADMIN'];
        if (in_array($user_role, $allowed)) {
            $this->user_role = $user_role;
        }
    }



    

}