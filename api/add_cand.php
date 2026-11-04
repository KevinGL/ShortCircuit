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

if(!isset($_POST["nb_contacts"]) || $_POST["nb_contacts"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Une erreur s'est produite"];
    header('location: ../add_cand.php');
    die();
}

$contacts = [];

for($i = 0 ; $i < $_POST["nb_contacts"] ; $i++)
{
    $nameContact = "contact_name" . ($i + 1);
    $emailContact = "contact_email" . ($i + 1);
    $phoneContact = "contact_phone" . ($i + 1);
    $roleContact = "contact_role" . ($i + 1);

    if(!isset($_POST[$nameContact]) || $_POST[$nameContact] === "" || !isset($_POST[$emailContact]) || $_POST[$emailContact] === "")
    {
        $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer nom et adresse mail du contact"];
        header('location: ../add_cand.php');
        die();
    }

    $contacts[] = [
        "name" => $_POST[$nameContact],
        "email" => $_POST[$emailContact],
        "phone" => $_POST[$phoneContact],
        "role" => $_POST[$roleContact],
        ];
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

$req = $pdo->query("SELECT id FROM candidacy ORDER BY id DESC LIMIT 1");
$response = $req->fetch();

$candId = $response["id"];

foreach($contacts as $contact)
{
    $req = $pdo->prepare("INSERT INTO contact (name, email, phone, role, cand_id) VALUES (:name, :email, :phone, :role, :cand_id)");
    $res = $req->execute([
        "name" => $contact["name"],
        "email" => $contact["email"],
        "phone" => $contact["phone"],
        "role" => $contact["role"],
        "cand_id" => $candId
    ]);

    if(!$res)
    {
        $_SESSION["flash"] = ["status" => "error", "message" => "Erreur interne"];
        header('location: ../add_cand.php');
        die();
    }
}

$_SESSION["flash"] = ["status" => "success", "message" => "Candidature ajoutée"];
header('location: ../add_cand.php');