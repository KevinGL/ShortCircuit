<?php

function getCandById(string $id)
{
    $candidacy = null;

    if($id === "")
    {
        $_SESSION["flash"] = ["status" => "error", "message" => "Id manquant"];
        header('location: ../index.php');
        return null;
    }

    require_once __DIR__ . "/../db/pdo.php";

    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT ca.society, ca.type, ca.status, ca.date, ca.infos, ca.id, co.name AS contact_name, co.email AS contact_email, co.phone AS contact_phone, co.linkedin AS contact_linkedin, co.role AS contact_role, co.id AS contact_id FROM candidacy ca LEFT JOIN contact co ON co.cand_id = ca.id WHERE ca.id = :id");
    $stmt->execute(["id" => $_GET["id"]]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row)
    {
        if ($candidacy === null)
        {
            $candidacy = [
                'society'  => $row['society'],
                'type'     => $row['type'],
                'status'   => $row['status'],
                'date'     => $row['date'],
                'infos'    => $row['infos'],
                'id'    => $row['id'],
                'contacts' => []
            ];
        }

        if (!empty($row['contact_name']))
        {
            $candidacy['contacts'][] = [
                'name'  => $row['contact_name'],
                'email' => $row['contact_email'],
                'phone' => $row['contact_phone'],
                'linkedin' => $row['contact_linkedin'],
                'role'  => $row['contact_role'],
                'id'  => $row['contact_id']
            ];
        }
    }

    return $candidacy;
}