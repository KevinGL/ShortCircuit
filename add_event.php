<?php

session_start();

require_once __DIR__ . "/utils/event.php";
require_once __DIR__ . "/api/get_last_event.php";

if(!isset($_SESSION["auth"]))
{
    header("location: auth/login.php");
    die();
}

if(!isset($_GET["society"]))
{
    header("location: home.php");
    die();
}

$lastEvent = getLastEventFromSociety($_GET["society"]);
$events = getNextEventsPossibles($lastEvent);

if(empty($events))
{
    $_SESSION["flash"] = ["status" => "error", "message" => "Pas d'événement possible"];
    header('location: ../home.php');
    die();
}

?>

<!doctype html>
<html lang="fr" class="h-full bg-slate-50">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ajout événement</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>

  <body class="min-h-full flex flex-col justify-between text-slate-800">

    <?php if(isset($_SESSION["flash"])): ?>
        <div id="flash" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">
            <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 p-6 max-w-md w-full text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </div>
                <h2 class="text-base font-medium text-slate-800 mb-6 leading-relaxed">
                    <?= $_SESSION["flash"]["message"] ?>
                </h2>
                <button id="ok_flash" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all">
                    OK
                </button>
            </div>
        </div>
        <?php unset($_SESSION["flash"]); ?>
    <?php endif ?>

    <header class="bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
            <h1 class="text-lg font-bold text-indigo-600">🚀 Nouvel Evénement</h1>
            <a href="home.php" class="text-sm font-medium text-slate-500 hover:text-indigo-600 transition flex items-center gap-1">
                &larr; Revenir à la chronologie
            </a>
        </div>
    </header>

    <main class="max-w-3xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="bg-white shadow-xl shadow-slate-100 rounded-2xl border border-slate-200 p-6 sm:p-8">

            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900">Ajouter un événement pour <?= $_GET["society"] ?></h2>
                <p class="text-sm text-slate-500 mt-1">Remplissez les informations de l'événement</p>
            </div>

            <form method="post" action="api/add_event.php" class="space-y-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Type d'événement</label>
                        <select name="type" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition">
                            <?php foreach($events as $e): ?>
                                <option value="<?= $e ?>"><?= $e ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Date de l'événement</label>
                        <input name="date" type="date" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Infos supplémentaires</label>
                    <textarea name="infos" type="infos" rows="3" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition" ></textarea>
                </div>

                <input type="text" name="society" value="<?= $_GET['society'] ?>" hidden />
                
                <button class="w-full sm:flex-1 px-4 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition">
                    Sauvegarder l'événement
                </button>
            </form>
        </div>

    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        Gestion de candidatures &bull; Tous droits réservés
    </footer>

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