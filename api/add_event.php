<?php

session_start();

if(!isset($_SESSION["auth"]))
{
    header("location: auth/login.php");
    die();
}

require_once __DIR__ . "/../db/pdo.php";

if(!isset($_POST["society"]) || $_POST["society"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer le nom de la société"];
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

$stmt = $pdo->prepare("SELECT id FROM society WHERE name = :name");
$stmt->execute(["name" => $_POST["society"]]);
$res = $stmt->fetch();
$societyId = $res["id"];

if(!$res)
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Une erreur s'est produite"];
    header('location: ../home.php');
    die();
}

$stmt = $pdo->prepare("SELECT e.date FROM event e JOIN society s ON e.society_id = s.id WHERE s.name = :society ORDER BY date DESC LIMIT 1");
$stmt->execute(["society" => $_POST["society"]]);
$res = $stmt->fetch();

if(strtotime($_POST["date"]) < strtotime($res["date"]))
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer une date ultérieure"];
    header('location: ../add_event.php?society=' . $_POST["society"]);
    die();
}

$stmt = $pdo->prepare("SELECT id, infos FROM event WHERE society_id = :society_id AND type = 'Repérage'");
$stmt->execute(["society_id" => $societyId]);
$res = $stmt->fetch();

$infos = $res["infos"];

if($res)
{
    $idToDelete = $res["id"];
    $stmt = $pdo->prepare("DELETE FROM event WHERE id = :id");
    $stmt->execute(["id" => $idToDelete]);
}

$stmt = $pdo->prepare("INSERT INTO event (type, society_id, infos, date) VALUES (:type, :society_id, :infos, :date)");
$stmt->execute([
    "type" => $_POST["type"],
    "society_id" => $societyId,
    "infos" => $infos . "\n" . $_POST["infos"],
    "date" => $_POST["date"]
]);

$_SESSION["flash"] = ["status" => "success", "message" => "Evénement ajouté avec succès"];
header('location: ../home.php');