<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use TUDublin\WebApplication;

$app = new WebApplication();
$app->run();