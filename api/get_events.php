<?php

require_once __DIR__ . "/../db/pdo.php";

function getEvents(): array
{
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT e.id, e.type, e.infos, e.date, s.name as society FROM event e JOIN society s ON e.society_id = s.id ORDER BY e.date");

    $events = $stmt->fetchAll();

    $res = [];
    foreach($events as $e)
    {
        $key = $e["society"];

        if(!isset($res[$key]))
        {
            $res[$key] = [];
        }

        $event = ["id" => $e["id"], "type" => $e["type"], "infos" => $e["infos"], "date" => $e["date"], "relaunch" => false];
    
        array_push($res[$key], $event);
    }

    foreach($res as $key => &$value)
    {
        foreach($value as &$event)
        {
            if(str_contains($event["type"], "Candidature ") && count($value) === 1)
            {
                $ts = strtotime($event["date"]);
                if(time() - $ts > 14 * 24 * 3600)
                {
                    $event["relaunch"] = true;
                }
            }
        }
    }

    return $res;
}

function getEventFromId(string $id)
{
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM event WHERE id = :id");
    $stmt->execute(["id" => $id]);

    return $stmt->fetch();
}