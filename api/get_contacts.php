<?php

require_once __DIR__ . "/../db/pdo.php";

function getContactsFromSociety(string $society): array
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT id FROM society WHERE name = :name");
    $stmt->execute(["name" => $society]);
    $res = $stmt->fetch();

    if(!$res)
    {
        return [];
    }

    $societyId = $res["id"];

    $stmt = $pdo->prepare("SELECT * FROM contact WHERE society_id = :society_id");
    $stmt->execute(["society_id" => $societyId]);
    $res = $stmt->fetchAll();

    return $res;
}