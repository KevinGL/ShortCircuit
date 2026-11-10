<?php

session_start();

require_once __DIR__ . "/../db/pdo.php";

$pdo = getPDO();

if(!isset($_GET["contact_id"]) || !isset($_GET["cand_id"]))
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Ids manquants"];
    header('location: ../home.php');
    die();
}

$stmt = $pdo->prepare("DELETE FROM contact WHERE id = :id");
$res = $stmt->execute(["id" => $_GET["contact_id"]]);

if(!$res)
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Erreur interne"];
    header('location: ../home.php');
    die();
}

$_SESSION["flash"] = ["status" => "success", "message" => "Contact supprimé"];
header('location: ../edit_cand.php?id=' . $_GET["cand_id"]);