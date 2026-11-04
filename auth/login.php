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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>

  <body class="h-full flex flex-col justify-between text-slate-800">

    <?php if(isset($_SESSION["flash"])): ?>
          <div id="flash" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">
              <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 p-6 max-w-md w-full text-center">
                  <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-50 text-rose-600 mb-4">
                      <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
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

    <header></header>

    <main class="max-w-md w-full mx-auto px-4 py-12">

      <div class="bg-white shadow-xl shadow-slate-100 rounded-2xl border border-slate-200 p-8 text-center">
              
        <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-2xl bg-indigo-50 text-indigo-600 mb-6 shadow-inner">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>

        <h1 class="text-2xl font-extrabold text-slate-900 mb-2">Accès protégé</h1>
        <p class="text-sm text-slate-500 mb-8">Veuillez entrer votre mot de passe pour continuer.</p>

        <form method="post" class="space-y-5 text-left">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Mot de passe</label>
                <input type="password" name="password" required autofocus class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition" placeholder="••••••••••••" />
            </div>

            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all">
              Envoyer
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

<?php else:

    auth($_POST["password"]);

endif ?>