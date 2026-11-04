<?php

session_start();

if(isset($_SESSION["auth"]))
{
    header("location: ../home.php");
    die();
}

require_once __DIR__ . "/auth.php";

?>

<?php if($_SERVER["REQUEST_METHOD"] === "GET"): ?>

<!doctype html>
<html lang="fr" class="h-full bg-slate-50">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Authentification</title>
  </head>

  <body>

  <?php if(isset($_SESSION["flash"])): ?>
      <div id="flash">
        <h2><?= $_SESSION["flash"]["message"] ?></h2>
        <button id="ok_flash">OK</button>
      </div>
    <?php unset($_SESSION["flash"]); ?>
  <?php endif ?>

  <main>

    <form method="post">
        <div>
            <label>Entrer le mot de passe</label>
            <input type="password" name="password" />
        </div>

        <button>Envoyer
        </button>
    </form>

  </main>

  </body>
</html>

<script>
    const okFlash = document.getElementById("ok_flash");

    if(okFlash)
    {
        document.getElementById("ok_flash").addEventListener("click", () =>
        {
            document.getElementById("flash").remove();
        });
    }
</script>

<?php else:

    auth($_POST["password"]);

endif ?>