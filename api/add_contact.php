<?php

session_start();

unset($_SESSION["society_last_cand"]);

require_once __DIR__ . "/../db/pdo.php";

$pdo = getPDO();

if(!isset($_POST["name"]) || $_POST["name"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer le nom du contact"];
    header('location: ../add_contact.php');
    die();
}

if(!isset($_POST["society"]) || $_POST["society"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer la société"];
    header('location: ../add_contacts.php');
    die();
}

$req = $pdo->prepare("SELECT id FROM candidacy WHERE society = :society");
$res = $req->execute(["society" => $_POST["society"]]);

if(!$res)
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Une erreur s'est produite"];
    header('location: ../add_contacts.php');
    die();
}

$candId = $req->fetch()["id"];

$req = $pdo->prepare("INSERT INTO contact (name, email, phone, role, cand_id) VALUES (:name, :email, :phone, :role, :cand_id)");
$res = $req->execute([
    "name" => $_POST["name"],
    "email" => $_POST["email"],
    "phone" => $_POST["phone"],
    "role" => $_POST["role"],
    "cand_id" => $candId
]);

if(!$res)
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Une erreur s'est produite"];
    header('location: ../add_cand.php');
    die();
}

$_SESSION["flash"] = ["status" => "success", "message" => "Contact ajouté avec succès"];
header('location: ../home.php');
die();