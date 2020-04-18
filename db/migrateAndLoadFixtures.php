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
    //['jdoe', 'pass', 'ROLE_SHOP'],
];

foreach ($userData as $cred) {
    $u = new User();
    $u->setUsername($cred[0]);
    $u->setPassword($cred[1]);
    $u->setUserType($cred[2]);

    $usersRepo->create($u);
}


for ($i = 0; $i < $limit; $i++){
    $name = $faker->name;
    $uname = str_replace(' ', '', $name);

    $u = new User();
    $u->setUsername($uname);
    $u->setPassword('pass');
    $u->setUserType('ROLE_SHOP');

    $usersRepo->create($u);

    $user = $usersRepo->searchByColumn('username', $uname);
    $user = array_pop($user);

    $o = new OwnerDetails();
    $o->setName($name);
    $o->setUserId($user->getID());

    $ownerDetailsRepo->create($o);
}



for ($i = 0; $i < $limit; $i++){
    $a = new CoffeeshopAddress;
//    $a->setCountry($faker->country);
    $a->setCountry('Ireland');
    $a->setCounty($faker->county);
    $a->setCity($faker->city);
    $a->setPostcode($faker->postcode);

    //split address from faker to seperate columns in address table
    $street = explode("\n", $faker->streetAddress);
    $a->setStreet1($street[0]);
    if (sizeof($street) > 1){
         $a->setStreet2($street[1]);
    }

    $csAddressRepo->create($a);
}

for ($i = 0; $i < $limit; $i++){
    $cs = new Coffeeshop();
    $cs->setAddressId($faker->unique()->randomDigit() + 1);
    $cs->setName($faker->company);
    $cs->setSummary($faker->text(500));
    $cs->setPaidContentId(null);

    $csRepo->create($cs);

}

for ($i = 0; $i < $limit; $i++){
    $r = new CoffeeshopReview();

    $r->setCoffeeshop($faker->randomDigit() + 1);
    $r->setExpense(rand(0,5));
    $r->setRating(rand(0,5));
    $r->setTitle($faker->text(100));
    $r->setReview($faker->text);

    $csReviewRepo->create($r);
}

for ($i = 0; $i < $limit; $i++){
    $c = new CoffeeshopComment();

    $c->setCoffeeshop($faker->randomDigit() + 1);
    $c->setName($faker->optional()->name);
    $c->setIsPublished(rand(0,1));
    $c->setMessage($faker->text(200));

    $csCommentRepo->create($c);
}

$drinkNames = [
'Espresso',
'Americano',
'Cappuccino',
'Mochaccino',
'Latte',
'Mocha Latte',
'AuLait',
'Mocha',
'Steamer',
'Chai Latte',
'Hot Tea',
'Frappaccino',
'Mocha Shake',
'Fruit Smoothie',
'Italian Soda',
'Iced Tea',
'Fresh-Squeezed Lemonade',
];
$drinkNamesSize = sizeof($drinkNames);

//for ($i = 0; $i < $limit /2; $i++){
//    $menu = new MenuList();
//    $menu->setMenuId();
//}
//
//for ($i = 0; $i < $limit; $i++){ }
//
//for ($i = 0; $i < $limit; $i++){
//    $pc = new CoffeeshopPaidContent();
//
////    $pc->set
//}



//$users =
//
//print_r($users);

