<?php

function getNextEventsPossibles(string $event): array
{
    $next =
    [
        "Repérage" => ["Candidature offre d'emploi", "Candidature spontanée", "Expiration"],
        "Candidature offre d'emploi" => ["Relance", "Réponse négative", "Réponse positive", "Expiration"],
        "Candidature spontanée" => ["Relance", "Réponse négative", "Réponse positive", "Expiration"],
        "Relance" => ["Réponse négative", "Réponse positive", "Expiration"],
        "Réponse positive" => ["Entretien d'embauche", "Expiration"],
        "Entretien d'embauche" => ["Embauche", "Réponse négative", "Expiration"]
    ];

    if($event === "")
    {
        return ["Repérage", "Candidature offre d'emploi", "Candidature spontanée"];
    }

    return $next[$event] ?? [];
}