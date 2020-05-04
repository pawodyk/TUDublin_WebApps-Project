<?php

require_once __DIR__ . '/../env/dbConstants.php';
require_once __DIR__ . '/../vendor/autoload.php';


use TUDublin\dbObjects\Coffeeshop;
use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\CoffeeshopAddress;
use TUDublin\dbObjects\CoffeeshopAddressRepository;
use TUDublin\dbObjects\CoffeeshopComment;
use TUDublin\dbObjects\CoffeeshopCommentRepository;
use TUDublin\dbObjects\CoffeeshopOwner;
use TUDublin\dbObjects\CoffeeshopOwnerRepository;
use TUDublin\dbObjects\CoffeeshopReview;
use TUDublin\dbObjects\CoffeeshopReviewRepository;
use TUDublin\dbObjects\MenuItem;
use TUDublin\dbObjects\MenuItemRepository;
use TUDublin\dbObjects\Picture;
use TUDublin\dbObjects\PictureRepository;
use TUDublin\dbObjects\User;
use TUDublin\dbObjects\UserRepository;

$faker = Faker\Factory::create('en_GB');
$limit = 10;

const PREDEFINED_USERS = [
    ['uname' => 'admin', 'pass' => 'admin', 'role' => 'ROLE_ADMIN'],
    ['uname' => 'user', 'pass' => 'user', 'role' => 'ROLE_STAFF'],
    ['uname' => 'owner', 'pass' => 'owner', 'role' => 'ROLE_SHOP'],
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
$csOwnerRepo = new CoffeeshopOwnerRepository();
$csReviewRepo = new CoffeeshopReviewRepository();
$menuItemRepo = new MenuItemRepository();
$pictureRepo = new PictureRepository();
$usersRepo = new UserRepository();

/* Table creation order to comply with foreign key restrictions:

1. user; coffeeshopaddreess; picture;       <- no fk
2. coffeeshopowner;                         <- fk in group 1
3. coffeeshop;                              <- fk in group 1 or 2
4. menuitem;                                <- fk in group 1 or 2 or 3
5. coffeeshopcomment coffeeshppreview       <- fk in group 1 or 2 or 3 or 4

*/

// drop tables
$csCommentRepo->dropTable();
$csReviewRepo->dropTable();

$menuItemRepo->dropTable();

$csRepo->dropTable();

$csOwnerRepo->dropTable();

$usersRepo->dropTable();
$csAddressRepo->dropTable();
$pictureRepo->dropTable();


// create tables
$usersRepo->createTable();
$csAddressRepo->createTable();
$pictureRepo->createTable();

$csOwnerRepo->createTable();

$csRepo->createTable();

$menuItemRepo->createTable();

$csCommentRepo->createTable();
$csReviewRepo->createTable();


// add fake data

for ($i = 0; $i < round($limit / 2); $i++) {

    // Set variables derived from name for more "real world" data
    $name = $faker->name;
    $uname = str_replace(' ', '', $name);


    // Create user object
    $u = new User();
    $u->setUsername($uname);
    $u->setPassword('pass');
    $u->setUserRole('ROLE_SHOP');

    $usersRepo->create($u);

    // get newly created user object from database to correctly link it with other tables
    $currentUser = $usersRepo->getUser($uname);

    // Create coffeshopowner object
    $csO = new CoffeeshopOwner();
    $csO->setName($name);
    $csO->setUserId($currentUser->getId());
    $csO->setBio($faker->text(500));

    $csOwnerRepo->create($csO);

    // make most fake owners have 1 coffeeshop to max 3 coffeeshops
    $noOfOwnedCS = $faker->numberBetween(1, 3);

    for ($j = 0; $j < $noOfOwnedCS; $j++) {

        $cs = new Coffeeshop();
        $cs->setName($faker->company . ' Coffee Shop');
        $cs->setOwnerId($currentUser->getId());
        $cs->setSummary($faker->text(500));

        $csRepo->create($cs);
    }
}

// create coffeshops without owners
for ($i = 0; $i < $limit; $i++) {
    $cs = new Coffeeshop();
    $cs->setName($faker->company . ' Coffee Shop');

    $csRepo->create($cs);
}

$coffeeshops = $csRepo->findAll();

for ($i = 0; $i < sizeof($coffeeshops); $i++) {
    $a = new CoffeeshopAddress;
    //    $a->setCountry($faker->country);
    //    $a->setCountry('Ireland');
    $a->setCounty($faker->county);
    $a->setCity($faker->city);
    $a->setPostcode($faker->postcode);

    //split address from faker to separate columns in address table
    $street = explode("\n", $faker->streetAddress);
    $a->setStreet1($street[0]);
    if (sizeof($street) > 1) {
        $a->setStreet2($street[1]);
    }

    $csAddressRepo->create($a);
}

$menuItterator = 1;
foreach ($coffeeshops as $cs){
    $cs->setAddressId($faker->unique()->numberBetween(1, sizeof($coffeeshops)));
//    var_dump($cs->getOwnerId());
    if (!is_null($cs->getOwnerId())){
        $cs->setMenuId($menuItterator++);
    }
    $csRepo->update($cs);
}

for ($i = 0; $i < $menuItterator * 5; $i++) {
    $ml = new MenuItem();
    $ml->setMenuId($faker->numberBetween(1, $menuItterator - 1));
    $ml->setItemName($drinkNames[rand(0, $drinkNamesSize - 1)]);
    $ml->setItemPrice($faker->randomFloat(2, 0.5, 5));

    $menuItemRepo->create($ml);
}


for ($i = 0; $i < sizeof($coffeeshops) * 2; $i++) {
    $r = new CoffeeshopReview();

    $r->setCoffeeshopId($faker->numberBetween(1, sizeof($coffeeshops)));
    $r->setExpense($faker->numberBetween(1, 5));
    $r->setRating($faker->numberBetween(1, 5));
    $r->setTitle($faker->text(100));
    $r->setReview($faker->text(500));
    $r->setReviewDate($faker->date());

    $csReviewRepo->create($r);
}

for ($i = 0; $i < $limit; $i++) {
    $c = new CoffeeshopComment();

    $c->setCoffeeshopId($faker->randomDigit() + 1);
    $c->setName($faker->optional()->name);
    $c->setIsPublished($faker->numberBetween(0, 1));
    $c->setMessage($faker->text(200));

    $csCommentRepo->create($c);
}

// add required user per project description
foreach (PREDEFINED_USERS as $cred) {
    $u = new User();
    $u->setUsername($cred['uname']);
    $u->setPassword($cred['pass']);
    $u->setUserRole($cred['role']);

    $usersRepo->create($u);
}

//$users =
//
//print_r($users);
