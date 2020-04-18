<?php

require_once __DIR__ . '/../env/dbConstants.php';
require_once  __DIR__ . '/../vendor/autoload.php';


use TUDublin\dbObjects\Coffeeshop;
use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\CoffeeshopAddress;
use TUDublin\dbObjects\CoffeeshopAddressRepository;
use TUDublin\dbObjects\CoffeeshopComment;
use TUDublin\dbObjects\CoffeeshopCommentRepository;
use TUDublin\dbObjects\CoffeeshopPaidContent;
use TUDublin\dbObjects\CoffeeshopPaidContentRepository;
use TUDublin\dbObjects\CoffeeshopReview;
use TUDublin\dbObjects\CoffeeshopReviewRepository;
use TUDublin\dbObjects\MenuItem;
use TUDublin\dbObjects\MenuItemRepository;
use TUDublin\dbObjects\MenuList;
use TUDublin\dbObjects\MenuListRepository;
use TUDublin\dbObjects\OwnerDetails;
use TUDublin\dbObjects\OwnerDetailsRepository;
use TUDublin\dbObjects\Picture;
use TUDublin\dbObjects\PictureRepository;
use TUDublin\dbObjects\User;
use TUDublin\dbObjects\UserRepository;

$faker = Faker\Factory::create('en_GB');
$limit = 10;

$csRepo = new CoffeeshopRepository();
$csAddressRepo = new CoffeeshopAddressRepository();
$csCommentRepo = new CoffeeshopCommentRepository();
$csPaidContentRepo = new CoffeeshopPaidContentRepository();
$csReviewRepo = new CoffeeshopReviewRepository();
$menuItemRepo = new MenuItemRepository();
$menuListRepo = new MenuListRepository();
$ownerDetailsRepo = new OwnerDetailsRepository();
$pictureRepo = new PictureRepository();
$usersRepo = new UserRepository();

/* Table creation order to comply with foreign key restrictions:

1. manuitem user
2. manulist ownerdetails
3. coffeeshoppaidcontent coffeeshopaddress
4. coffeeshop
5. coffeeshopcomment coffeeshopreview picture

*/

$usersRepo->dropTable();            $usersRepo->createTable();
$ownerDetailsRepo->dropTable();     $ownerDetailsRepo->createTable();
$menuItemRepo->dropTable();         $menuItemRepo->createTable();
$menuListRepo->dropTable();         $menuListRepo->createTable();
$csPaidContentRepo->dropTable();    $csPaidContentRepo->createTable();
$csAddressRepo->dropTable();        $csAddressRepo->createTable();
$csRepo->dropTable();               $csRepo->createTable();
$csCommentRepo->dropTable();        $csCommentRepo->createTable();
$csReviewRepo->dropTable();         $csReviewRepo->createTable();
$pictureRepo->dropTable();          $pictureRepo->createTable();

$userData = [
    ['admin', 'admin', 'ROLE_ADMIN'],
    ['user', 'user', 'ROLE_STAFF'],
    ['owner', 'owner', 'ROLE_SHOP'],
    ['jdoe', 'pass', 'ROLE_SHOP'],
];

foreach ($userData as $cred) {
    $u = new User();
    $u->setUsername($cred[0]);
    $u->setPassword($cred[1]);
    $u->setUserType($cred[2]);

    $usersRepo->create($u);
}


//$users = $usersRepo->findAll();

//var_dump($users);

