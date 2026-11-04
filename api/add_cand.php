<?php

session_start();

require_once __DIR__ . "/../db/pdo.php";

$pdo = getPDO();

if(!isset($_POST["society"]) || $_POST["society"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer le nom de la société"];
    header('location: ../add_cand.php');
    die();
}

if(!isset($_POST["date"]) || $_POST["date"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer une date d'ajout"];
    header('location: ../add_cand.php');
    die();
}

$req = $pdo->prepare("INSERT INTO candidacy (society, type, status, date, infos) VALUES (:society, :type, :status, :date, :infos)");
$res = $req->execute([
    "society" => $_POST["society"],
    "type" => $_POST["type"],
    "status" => "added",
    "date" => $_POST["date"],
    "infos" => $_POST["infos"],
]);

if(!$res)
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Erreur interne"];
    header('location: ../add_cand.php');
    die();
}

$_SESSION["society_last_cand"] = $_POST["society"];

header('location: ../add_contacts.php');