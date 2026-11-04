<?php

function auth(string $password)
{
    $vars = getEnvVar();

    if(password_verify($password, $vars["PASSWORD"]))
    {
        $_SESSION["auth"] = true;
        header("location: ../home.php");
        die();
    }

    else
    {
        $_SESSION["flash"] = ["status" => "error", "message" => "Mauvaise mot de passe"];
        header("location: login.php");
        die();
    }
}