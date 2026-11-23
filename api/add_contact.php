<?php

session_start();

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

$stmt = $pdo->prepare("SELECT id FROM society WHERE name = :society");
$stmt->execute(["society" => $_POST["society"]]);
$res = $stmt->fetch();

if(!$res)
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Une erreur s'est produite"];
    header('location: ../add_contacts.php');
    die();
}

$societyId = $res["id"];

$req = $pdo->prepare("INSERT INTO contact (name, email, phone, linkedin, role, society_id) VALUES (:name, :email, :phone, :linkedin, :role, :society_id)");
$res = $req->execute([
    "name" => $_POST["name"],
    "email" => $_POST["email"],
    "phone" => $_POST["phone"],
    "linkedin" => $_POST["linkedin"],
    "role" => $_POST["role"],
    "society_id" => $societyId
]);

if(!$res)
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Une erreur s'est produite"];
    header('location: ../add_cand.php');
    die();
}

$_SESSION["flash"] = ["status" => "success", "message" => "Contact ajouté avec succès"];
header('location: ../home.php');