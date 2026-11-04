<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!empty($_SERVER))
{
  $_ENV = array_merge($_ENV, $_SERVER);
}

if(!isset($_SESSION["auth"]))
{
  header("location: auth/login.php");
  die();
}

else
{
  header("location: home.php");
  die();
}

?>
