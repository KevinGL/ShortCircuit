<?php

require_once __DIR__ . "/../db/pdo.php";

function getCompanies(): array
{
    $pdo = getPDO();

    $stmt = $pdo->query("SELECT name FROM society");
    $res = $stmt->fetchAll();

    if(!$res)
    {
        return [];
    }

    return $res;
}