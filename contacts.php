<?php

session_start();

require_once __DIR__ . "/api/get_contacts.php";

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

$contacts = getContactsFromSociety(htmlspecialchars($_GET["society"]));

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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
            <h1 class="text-xl font-bold text-indigo-600">🚀 Contacts chez <?= htmlspecialchars($_GET["society"]) ?></h1>
            <div>
                <a href="auth/logout.php" class="inline-flex items-center px-3 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 transition">
                    Se déconnecter
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <?php
            $contacts = $contacts ?? [];
            $societyName = htmlspecialchars((string) $_GET["society"]);
            $societyParam = urlencode((string) $_GET["society"]);
        ?>

        <!-- Barre supérieure de la section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <a href="home.php" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-indigo-600 transition mb-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Retour aux sociétés
            </a>
            <h2 class="text-2xl font-bold text-slate-800">
            Répertoire — <span class="text-indigo-600"><?= $societyName ?></span>
            </h2>
        </div>

        <a
            href="add_contacts.php?society=<?= $societyParam ?>"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Ajouter un contact
        </a>
        </div>

        <!-- Liste des contacts -->
        <?php if (empty($contacts)): ?>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800 mb-1">Aucun contact enregistré</h3>
                <p class="text-sm text-slate-500 mb-5">Ajoute un recruteur, manager ou contact clé pour <?= $societyName ?>.</p>
                <a
                    href="add_contacts.php?society=<?= $societyParam ?>"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition"
                    >
                    + Ajouter un premier contact
                </a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm divide-y divide-slate-100 overflow-hidden">
                <?php foreach ($contacts as $contact): ?>
                    <?php
                        $id    = urlencode((string) ($contact['id'] ?? ''));
                        $name  = htmlspecialchars((string) ($contact['name'] ?? 'Contact sans nom'));
                        $role  = htmlspecialchars((string) ($contact['role'] ?? ''));
                        $email = htmlspecialchars((string) ($contact['email'] ?? ''));
                        $phone = htmlspecialchars((string) ($contact['phone'] ?? ''));
                        
                        $rawLinkedin = trim((string) ($contact['linkedin'] ?? ''));
                        $isValidLinkedin = $rawLinkedin !== '' && preg_match('#^https?://#i', $rawLinkedin);
                        $linkedinUrl = $isValidLinkedin ? htmlspecialchars($rawLinkedin) : '';

                        // Initiales pour l'avatar
                        $parts = preg_split('/\s+/', trim((string) ($contact['name'] ?? 'C')));
                        $initials = strtoupper(
                            mb_substr($parts[0] ?? 'C', 0, 1) .
                            (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : '')
                        );
                    ?>

                    <div class="p-4 sm:p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-slate-50/80 transition">
                        <!-- Infos du contact -->
                        <div class="flex items-start sm:items-center gap-4 min-w-0">
                            <div class="h-11 w-11 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 font-semibold text-sm flex items-center justify-center shrink-0">
                                <?= htmlspecialchars($initials) ?>
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm sm:text-base font-semibold text-slate-800 truncate">
                                    <?= $name ?>
                                </p>
                                <?php if ($role !== ''): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <?= $role ?>
                                    </span>
                                <?php endif; ?>
                                </div>

                                <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs sm:text-sm text-slate-500">
                                    <?php if ($email !== ''): ?>
                                        <a href="mailto:<?= $email ?>" class="inline-flex items-center gap-1.5 hover:text-indigo-600 transition">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                            </svg>
                                            <?= $email ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($phone !== ''): ?>
                                        <a href="tel:<?= preg_replace('/[^+\d]/', '', $phone) ?>" class="inline-flex items-center gap-1.5 hover:text-indigo-600 transition">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                            </svg>
                                            <?= $phone ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($linkedinUrl !== ''): ?>
                                        <a
                                            href="<?= $linkedinUrl ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 font-medium text-indigo-600 hover:text-indigo-700 hover:underline transition"
                                            >
                                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M19 3A2 2 0 0 1 21 5V19A2 2 0 0 1 19 21H5A2 2 0 0 1 3 19V5A2 2 0 0 1 5 3H19M18.5 18.5V13.2A3.26 3.26 0 0 0 15.24 9.94C14.39 9.94 13.4 10.46 12.92 11.24V10.13H10.13V18.5H12.92V13.57C12.92 12.8 13.54 12.17 14.31 12.17A1.4 1.4 0 0 1 15.71 13.57V18.5H18.5M6.88 8.56A1.68 1.68 0 0 0 8.56 6.88C8.56 5.95 7.81 5.19 6.88 5.19A1.69 1.69 0 0 0 5.19 6.88C5.19 7.81 5.95 8.56 6.88 8.56M8.27 18.5V10.13H5.5V18.5H8.27Z" />
                                            </svg>
                                            LinkedIn
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Modifier / Supprimer -->
                        <div class="flex items-center gap-2 self-end md:self-center shrink-0">
                            <a
                                href="api/edit_contact.php?id=<?= $id ?>"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 hover:text-indigo-600 transition"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Modifier
                            </a>

                            <div
                                id="<?= $id ?>"
                                class="delete inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition hover:cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Supprimer
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        Gestion de candidatures &bull; Tous droits réservés
    </footer>

  </body>
</html>

<script>
    const buttons = document.querySelectorAll(".delete");
    buttons.forEach((button) =>
    {
        button.addEventListener("click", () =>
        {
            const res = confirm('Supprimer ce contact ?');
            if(res)
            {
                window.location = "/api/del_contact.php?id=" + encodeURIComponent(button.id);
            }
        });
    });
</script>