<?php

session_start();

require_once __DIR__ . "/../db/pdo.php";

$pdo = getPDO();

if(!isset($_POST["society"]) || $_POST["society"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer le nom de la société"];
    header('location: ../index.php');
    die();
}

if(!isset($_POST["date"]) || $_POST["date"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Veuillez indiquer une date d'ajout"];
    header('location: ../index.php');
    die();
}

if(!isset($_POST["id"]) || $_POST["id"] === "")
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Une erreur s'est produite"];
    header('location: ../index.php');
    die();
}

//////////////////////////////////////////////////////////////

$stmt = $pdo->prepare("UPDATE candidacy SET society = :society, type = :type, status = :status, date = :date, infos = :infos WHERE id = :id");
$stmt->execute([
        "society" => $_POST["society"],
        "type" => $_POST["type"],
        "status" => $_POST["status"],
        "date" => $_POST["date"],
        "infos" => $_POST["infos"],
        "id" => $_POST["id"]
    ]);

$paramsContact = [];

foreach($_POST as $key => $value)
{
    if(strpos($key, "contact_") === 0)
    {
        $params = explode("_", $key);

        $col = $params[1];
        $id = $params[2];

        $paramsContact[$id][$col] = $value;
    }
}

foreach($paramsContact as $key => $value)
{
    $stmt = $pdo->prepare("UPDATE contact SET name = :name, email = :email, phone = :phone, role = :role WHERE id = :id");
    $stmt->execute([
        "name" => $value["name"],
        "email" => $value["email"],
        "phone" => $value["phone"],
        "role" => $value["role"],
        "id" => $key
    ]);
}

$_SESSION["flash"] = ["status" => "success", "message" => "Candidature mise à jour"];
header('location: ../index.php');