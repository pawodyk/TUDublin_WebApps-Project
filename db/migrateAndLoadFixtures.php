<?php

require_once __DIR__ . '/../env/dbConstants.php';
require_once  __DIR__ . '/../vendor/autoload.php';

use TUDublin\dbObjects\User;
use TUDublin\dbObjects\UserRepository;

$usersRepo = new UserRepository();

$usersRepo->dropTable();
$usersRepo->createTable();

$credentials = [
    ['admin', 'admin', 'ROLE_ADMIN'],
    ['user', 'user', 'ROLE_STAFF'],
    ['owner', 'owner', 'ROLE_SHOP'],
    ['jdoe', 'pass', 'ROLE_SHOP'],
];

foreach ($credentials as $cred) {
    $u = new User();
    $u->setUsername($cred[0]);
    $u->setPassword($cred[1]);
    $u->setUserType($cred[2]);

    $usersRepo->create($u);

}


$users = $usersRepo->findAll();

var_dump($users);

