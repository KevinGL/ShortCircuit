<?php

session_start();

if(!isset($_SESSION["auth"]))
{
    header("location: auth/login.php");
    die();
}

require_once __DIR__ . "/../db/pdo.php";

$pdo = getPDO();

if(!isset($_GET["id"]))
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Id manquant"];
    header('location: ../home.php');
    die();
}

$stmt = $pdo->prepare("DELETE FROM contact WHERE id = :id");
$res = $stmt->execute(["id" => $_GET["id"]]);

if(!$res)
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Erreur interne"];
    header('location: ../home.php');
    die();
}

$_SESSION["flash"] = ["status" => "success", "message" => "Contact supprimé"];
header('location: ../home.php');