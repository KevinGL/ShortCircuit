<?php

session_start();

if(!isset($_SESSION["auth"]))
{
    header("location: auth/login.php");
    die();
}

require_once __DIR__ . "/../db/pdo.php";

if(!isset($_POST["id"]) || $_POST["id"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Une erreur s'est produite"];
    header('location: ../home.php');
    die();
}

if(!isset($_POST["type"]) || $_POST["type"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer un type"];
    header('location: ../add_event.php?society=' . $_POST["society"]);
    die();
}

if(!isset($_POST["date"]) || $_POST["date"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer une date d'ajout"];
    header('location: ../add_event.php?society=' . $_POST["society"]);
    die();
}

$pdo = getPDO();

$stmt = $pdo->prepare("UPDATE event SET type = :type, date = :date, infos = :infos WHERE id = :id");
$stmt->execute([
    "type" => $_POST["type"],
    "date" => $_POST["date"],
    "infos" => $_POST["infos"],
    "id" => $_POST["id"]
]);

$_SESSION["flash"] = ["status" => "success", "message" => "Evénement mis à jour avec succès"];
header('location: ../home.php');