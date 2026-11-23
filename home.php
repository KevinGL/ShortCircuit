<?php

session_start();

if(!isset($_SESSION["auth"]))
{
    header("location: auth/login.php");
    die();
}

require_once __DIR__ . "/api/get_events.php";

$events = getEvents();

$styles =
[
    'Candidature offre d\'emploi' =>
    [
        'label' => 'Candidature offre d\'emploi',
        'dot'   => 'bg-blue-600 ring-4 ring-blue-100',
        'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
    ],

    'Candidature spontanée' =>
    [
        'label' => 'Candidature spontanée',
        'dot'   => 'bg-blue-600 ring-4 ring-blue-100',
        'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
    ],

    'Relance' =>
    [
        'label' => 'Relance',
        'dot'   => 'bg-amber-500 ring-4 ring-amber-100',
        'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
    ],

    'Réponse positive' =>
    [
        'label' => 'Réponse positive',
        'dot'   => 'bg-emerald-600 ring-4 ring-emerald-100',
        'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ],

    'Réponse négative' =>
    [
        'label' => 'Réponse négative',
        'dot'   => 'bg-rose-600 ring-4 ring-rose-100',
        'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
    ],

    'Entretien d\'embauche' =>
    [
        'label' => 'Entretien d\'embauche',
        'dot'   => 'bg-purple-600 ring-4 ring-purple-100',
        'badge' => 'bg-purple-50 text-purple-700 border-purple-200',
    ],
];

$defaultStyle = [
    'label' => null,
    'dot'   => 'bg-slate-500 ring-4 ring-slate-100',
    'badge' => 'bg-slate-50 text-slate-700 border-slate-200',
];

?>

<!doctype html>
<html lang="fr" class="h-full bg-slate-50">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tableau de bord — Accueil</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>

  <body class="bg-slate-50 text-slate-800 min-main flex flex-col min-h-screen">

  <?php if(isset($_SESSION["flash"])): ?>
      <div id="flash" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">
            <!-- Carte de notification -->
            <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 p-6 max-w-md w-full text-center transform transition-all animate-in fade-in zoom-in duration-200">
                
                <!-- Icône optionnelle pour embellir -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </div>

                <!-- Message -->
                <h2 class="text-base font-medium text-slate-800 mb-6 leading-relaxed">
                    <?= $_SESSION["flash"]["message"] ?? '' ?>
                </h2>

                <!-- Bouton OK -->
                <button id="ok_flash" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    OK
                </button>
            </div>
        </div>
    <?php unset($_SESSION["flash"]); ?>
  <?php endif ?>

  <header class="bg-white border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-16 py-2.5 sm:py-0 flex flex-wrap sm:flex-nowrap justify-between items-center gap-2 sm:gap-4">
      <!-- Titre -->
      <h1 class="text-base sm:text-xl font-bold text-indigo-600 truncate">
        🚀 Dashboard Candidatures
      </h1>

      <!-- Actions de navigation -->
      <nav class="flex items-center gap-2 sm:gap-3 shrink-0" aria-label="Navigation principale">
        <a
          href="new_time.php"
          class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-xs transition"
        >
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          <span>Nouvelle chronologie</span>
        </a>

        <a
          href="auth/logout.php"
          class="inline-flex items-center px-3 py-2 border border-slate-300 shadow-2xs text-xs sm:text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition"
        >
          <span class="hidden sm:inline">Se déconnecter</span>
          <span class="sm:hidden">Déconnexion</span>
        </a>
      </nav>
    </div>
  </header>

  <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col gap-6 p-4 bg-slate-50 min-h-screen">
      <?php foreach ($events as $company => $companyEvents): ?>

        <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-6 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">

          <!-- Colonne gauche : Nom de la société + lien vers la page Contacts -->
          <div class="md:w-52 shrink-0 flex flex-col items-start gap-1.5">
            <div class="w-full font-semibold text-slate-800 truncate" title="<?= htmlspecialchars($company) ?>">
              <?= htmlspecialchars($company) ?>
            </div>

            <a
              href="contacts.php?society=<?= htmlspecialchars($company) ?>"
              class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80 transition"
              title="Voir les contacts de <?= htmlspecialchars($company) ?>"
            >
              <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
              </svg>
              <span>Contacts</span>
            </a>
          </div>

          <!-- Zone droite : Trait horizontal + événements chronologiques + lien d'ajout -->
          <div class="relative flex-1 overflow-x-auto py-4">
            <div class="relative flex items-center min-w-max pr-8">
              <!-- Trait horizontal continu -->
              <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-slate-300 z-0"></div>

              <div class="relative z-10 flex items-center gap-8">
                <?php foreach ($companyEvents as $event): ?>
                  <?php
                    $typeKey       = $event['type'] ?? '';
                    $style         = $styles[$typeKey] ?? $defaultStyle;
                    $label         = $style['label'] ?? htmlspecialchars($event['type'] ?? '');
                    $dateStr       = !empty($event['date']) ? date('d/m', strtotime($event['date'])) : '';
                    $needsRelaunch = !empty($event['relaunch']);
                  ?>

                  <div class="relative flex flex-col items-center group">
                    <!-- Pictogramme d'urgence au-dessus si relance nécessaire -->
                    <?php if ($needsRelaunch): ?>
                      <span
                        class="absolute -top-5 inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-600 text-white shadow-sm animate-bounce"
                        title="À relancer"
                      >
                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                      </span>
                    <?php endif; ?>

                    <!-- Pastille posée sur le trait -->
                    <span id="<?= $event['id'] ?>_<?= $company ?>" class="event w-3.5 h-3.5 rounded-full transition-transform duration-150 group-hover:scale-125 <?= $needsRelaunch ? 'bg-red-600 ring-4 ring-red-100' : $style['dot'] ?>"></span>

                    <!-- Étiquette type + date -->
                    <div class="mt-2 px-2.5 py-1 rounded-md border text-xs font-medium whitespace-nowrap flex items-center gap-1.5 <?= $needsRelaunch ? 'bg-red-50 text-red-700 border-red-300 ring-1 ring-red-200' : $style['badge'] ?>">
                      <span><?= $label ?></span>
                      <?php if ($dateStr): ?>
                        <span class="opacity-75">· <?= $dateStr ?></span>
                      <?php endif; ?>
                      <?php if ($needsRelaunch): ?>
                        <span class="ml-0.5 px-1.5 py-0.2 rounded bg-red-600 text-white text-[10px] font-semibold uppercase tracking-wide">
                          Relancer
                        </span>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>

                <!-- Lien d'ajout d'événement à l'extrémité droite de la chronologie -->
                <a
                  href="add_event.php?society=<?= htmlspecialchars($company) ?>"
                  class="relative flex flex-col items-center group focus:outline-none"
                  title="Ajouter un événement pour <?= htmlspecialchars($company) ?>"
                >
                  <!-- Cercle "+" sur le trait -->
                  <span class="w-6 h-6 rounded-full bg-white border-2 border-dashed border-slate-400 text-slate-600 flex items-center justify-center shadow-xs transition-all duration-150 group-hover:border-blue-600 group-hover:bg-blue-600 group-hover:text-white group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                  </span>

                  <!-- Libellé sous le "+" -->
                  <span class="mt-2 px-2.5 py-1 rounded-md border border-dashed border-slate-300 bg-slate-50 text-slate-600 text-xs font-medium whitespace-nowrap transition-colors duration-150 group-hover:border-blue-300 group-hover:bg-blue-50 group-hover:text-blue-700">
                    + Ajouter
                  </span>
                </a>

              </div>
            </div>
          </div>

        </div>
      <?php endforeach; ?>
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

  const events = document.querySelectorAll(".event");
  events.forEach((e) =>
  {
      e.addEventListener("click", () =>
      {
        const [id, company] = e.id.split("_");
        window.location = "/edit_event.php?id=" + encodeURIComponent(id) + "&society=" + encodeURIComponent(company);
      });
  });

</script>