<?php

namespace TUDublin\dbObjects;

class User {

    const CREATE_TABLE_SQL =
<<<HERE
CREATE TABLE user (
    id integer PRIMARY KEY AUTO_INCREMENT,
    username varchar(50) NOT NULL,
    password varchar(255) NOT NULL,
    user_type varchar(10) NOT NULL
);
HERE;

    private $id;
    private $username;
    private $password;
    private $user_type;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

//    need to check do I need to allow setting of id for users
//    /**
//     * @param string $id
//     */
//    public function setId($id)
//    {
//        $this->id = $id;
//    }

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
        $this->username = $username;
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
    public function getUserType()
    {
        return $this->user_type;
    }

    /**
     * @param string $user_type
     */
    public function setUserType($user_type)
    {
        $allowed = ['ROLE_STAFF', 'ROLE_SHOP', 'ROLE_ADMIN'];
        if (in_array($user_type, $allowed)) {
            $this->user_type = $user_type;
        }
    }



    

}