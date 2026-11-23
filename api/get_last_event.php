<?php

require_once __DIR__ . "/../db/pdo.php";

function getLastEventFromSociety(string $society): string
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT e.type FROM event e JOIN society s ON e.society_id = s.id WHERE s.name = :society ORDER BY date DESC LIMIT 1");
    $stmt->execute(["society" => $society]);
    $res = $stmt->fetch();

    return $res["type"];
}

function getBeforeLastEventFromSociety(string $society): string
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT e.type FROM event e JOIN society s ON e.society_id = s.id WHERE s.name = :society ORDER BY date DESC");
    $stmt->execute(["society" => $society]);
    $res = $stmt->fetchAll();

    if(count($res) < 2)
    {
        return "";
    }

    return $res[1]["type"];
}