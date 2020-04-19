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
use TUDublin\dbObjects\CoffeeshopMenu;
use TUDublin\dbObjects\CoffeeshopMenuRepository;
use TUDublin\dbObjects\MenuList;
use TUDublin\dbObjects\MenuListRepository;
use TUDublin\dbObjects\Picture;
use TUDublin\dbObjects\PictureRepository;
use TUDublin\dbObjects\User;
use TUDublin\dbObjects\UserRepository;

$faker = Faker\Factory::create('en_GB');
$limit = 10;

const PREDEFINED_USERS = [
    ['id' => 100,     'uname' => 'admin',    'pass' => 'admin',   'role' => 'ROLE_ADMIN'],
    ['id' => 101,     'uname' => 'user',     'pass' => 'user',    'role' => 'ROLE_STAFF'],
    ['id' => 102,     'uname' => 'owner',    'pass' => 'owner',   'role' => 'ROLE_SHOP'],
];

$local_users = [];

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

// create repos
$csRepo = new CoffeeshopRepository();
$csAddressRepo = new CoffeeshopAddressRepository();
$csCommentRepo = new CoffeeshopCommentRepository();
$csPaidContentRepo = new CoffeeshopPaidContentRepository();
$csReviewRepo = new CoffeeshopReviewRepository();
$csMenuRepo = new CoffeeshopMenuRepository();
$menuListRepo = new MenuListRepository();
$pictureRepo = new PictureRepository();
$usersRepo = new UserRepository();

/* Table creation order to comply with foreign key restrictions:

1. user coffeeshopaddreess             			<- no fk
2. coffeeshopmenu                               <- fk in group 1
3. menulist coffeeshoppaidcontent				<- fk in group 1 or 2
4. coffeeshop                           		<- fk in group 1 or 2 or 3
5. coffeeshopcomment coffeeshppreview picture   <- fk in group 1 or 2 or 3 or 4

*/

// drop tables
$csCommentRepo->dropTable(); $csReviewRepo->dropTable(); $pictureRepo->dropTable();

$csRepo->dropTable();

$menuListRepo->dropTable(); $csPaidContentRepo->dropTable();

$csMenuRepo->dropTable();

$usersRepo->dropTable(); $csAddressRepo->dropTable();


// create tables
$usersRepo->createTable(); $csAddressRepo->createTable();

$csMenuRepo->createTable();

$menuListRepo->createTable(); $csPaidContentRepo->createTable();

$csRepo->createTable();

$csCommentRepo->createTable(); $csReviewRepo->createTable(); $pictureRepo->createTable();

// add fake data
for ($i = 0; $i < $limit; $i++){
     $m = new CoffeeshopMenu();

     $m->getOwnerId(null);

     $csMenuRepo->create($m);
 }

 for ($i = 0; $i < $limit * 5; $i++){
    $ml = new MenuList();
    $ml->setMenuId(rand(1,$limit));
    $ml->setItemName($drinkNames[rand(0, $drinkNamesSize - 1)]);
    $ml->setItemPrice($faker->randomFloat(2,0.5, 5));

    $menuListRepo->create($ml);
 }



for ($i = 0; $i < $limit; $i++) {
    $name = $faker->name;
    $uname = str_replace(' ', '', $name);

    $u = new User();
    $u->setUsername($uname);
    $u->setPassword('pass');
    $u->setUserType('ROLE_SHOP');

    $usersRepo->create($u);

    $dbQuerySet = $usersRepo->searchByColumn('username', $uname);
    $user = array_pop($dbQuerySet);

    $pc = new CoffeeshopPaidContent();

    $pc->setSummary($faker->text(500));
    $pc->setMenuId($user->getId());
    $pc->setOwnerId($user->getId());
    $pc->setOwnerName($name);

    $csPaidContentRepo->create($pc);
}

for ($i = 0; $i < $limit; $i++) {
    $a = new CoffeeshopAddress;
    //    $a->setCountry($faker->country);
    $a->setCountry('Ireland');
    $a->setCounty($faker->county);
    $a->setCity($faker->city);
    $a->setPostcode($faker->postcode);

    //split address from faker to seperate columns in address table
    $street = explode("\n", $faker->streetAddress);
    $a->setStreet1($street[0]);
    if (sizeof($street) > 1) {
        $a->setStreet2($street[1]);
    }

    $csAddressRepo->create($a);
}

for ($i = 0; $i < $limit; $i++) {
    $cs = new Coffeeshop();
    $cs->setAddressId($faker->unique()->numberBetween(1,$limit));
    $cs->setName($faker->company);
    $cs->setSummary($faker->text(500));
    $cs->setPaidContentId($faker->optional(0.8)->numberBetween(1,$limit));

    $csRepo->create($cs);
}

for ($i = 0; $i < $limit; $i++) {
    $r = new CoffeeshopReview();

    $r->setCoffeeshopId($faker->randomDigit() + 1);
    $r->setExpense(rand(0, 5));
    $r->setRating(rand(0, 5));
    $r->setTitle($faker->text(100));
    $r->setReview($faker->text);

    $csReviewRepo->create($r);
}

for ($i = 0; $i < $limit; $i++) {
    $c = new CoffeeshopComment();

    $c->setCoffeeshopId($faker->randomDigit() + 1);
    $c->setName($faker->optional()->name);
    $c->setIsPublished(rand(0, 1));
    $c->setMessage($faker->text(200));

    $csCommentRepo->create($c);
}

//// fix fk to point to the right data

// add required user per project description
foreach (PREDEFINED_USERS as $cred) {
    $u = new User();
    $u->setUsername($cred['uname']);
    $u->setPassword($cred['pass']);
    $u->setUserType($cred['role']);

    $usersRepo->create($u);
}

//$users =
//
//print_r($users);
