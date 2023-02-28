<?php
include('vendor/autoload.php');

use \app\App\App;
use \Dejurin\GoogleTranslateForFree;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new App();
$app->start();



