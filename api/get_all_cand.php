<?php

require_once __DIR__ . "/../db/pdo.php";
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (!empty($_SERVER)) {
    $_ENV = array_merge($_ENV, $_SERVER);
}

$pdo = getPDO();

$res = $pdo->query("SELECT * FROM candidacy");
$cand = $res->fetchAll();

header('Content-Type: application/json');
http_response_code(200);
echo json_encode($cand);