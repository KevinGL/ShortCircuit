<?php

session_start();

require_once __DIR__ . "/../db/pdo.php";

if(!isset($_POST["society"]) || $_POST["society"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer une société"];
    header("location: ../new_time.php");
    die();
}

if(!isset($_POST["type"]) || $_POST["type"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer un type"];
    header("location: ../new_time.php");
    die();
}

if(!isset($_POST["date"]) || $_POST["date"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer une date d'ajout"];
    header("location: ../new_time.php");
    die();
}

if($_POST["type"] !== "Repérage" && $_POST["type"] !== "Candidature offre d'emploi" && $_POST["type"] !== "Candidature spontanée")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Une erreur s'est produite"];
    header("location: ../new_time.php");
    die();
}

$pdo = getPDO();

$stmt = $pdo->prepare("SELECT id FROM society WHERE name = :society");
$stmt->execute(["society" => $_POST["society"]]);
$res = $stmt->fetch();

if($res)
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Cette société existe déjà"];
    header("location: ../add_event.php?society=" . $_POST["society"]);
    die();
}

$stmt = $pdo->prepare("INSERT INTO society (name) VALUES (:name)");
$stmt->execute(["name" => $_POST["society"]]);

$newId = $pdo->lastInsertId();

$stmt = $pdo->prepare("INSERT INTO event (type, society_id, infos, date) VALUES (:type, :society_id, :infos, :date)");
$stmt->execute([
    "type" => $_POST["type"],
    "society_id" => $newId,
    "infos" => $_POST["infos"],
    "date" => $_POST["date"]
]);

$_SESSION["flash"] = ["status" => "success", "message" => "Nouvelle chronologie ajoutée"];
header("location: ../home.php");