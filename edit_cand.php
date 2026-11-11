<?php

session_start();

if(!isset($_SESSION["auth"]))
{
    header("location: auth/login.php");
    die();
}

require_once "api/get_cand_by_id.php";

$candidacy = getCandById($_GET["id"]);

?>

<!doctype html>
<html lang="fr" class="h-full bg-slate-50">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mise à jour candidature</title>
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
            <h1 class="text-lg font-bold text-indigo-600">🚀 Màj Candidature</h1>
            <a href="home.php" class="text-sm font-medium text-slate-500 hover:text-indigo-600 transition flex items-center gap-1">
                &larr; Revenir au calendrier
            </a>
        </div>
    </header>

    <main class="max-w-3xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="bg-white shadow-xl shadow-slate-100 rounded-2xl border border-slate-200 p-6 sm:p-8">

            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900">Mettre à jour une candidature</h2>
                <p class="text-sm text-slate-500 mt-1">Remplissez les informations de l'entreprise et de vos contacts clés.</p>
            </div>

            <form method="post" action="api/update_cand.php" class="space-y-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Société</label>
                        <input name="society" type="text" value="<?= $candidacy['society'] ?>" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Type de candidature</label>
                        <select name="type" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition">
                            <option value="job_offer" <?php if($candidacy["type"] === "spontaneous") echo "selected" ?>>Offre d'emploi</option>
                            <option value="spontaneous" <?php if($candidacy["type"] === "spontaneous") echo "selected" ?>>Spontanée</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Date d'ajout</label>
                        <input name="date" type="date" value="<?= $candidacy['date'] ?>" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Statut</label>
                        <select name="status" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition">
                            <option value="added" <?php if($candidacy["status"] === "added") echo "selected" ?>>Ajoutée</option>
                            <option value="treated" <?php if($candidacy["status"] === "treated") echo "selected" ?>>Traitée</option>
                            <option value="relaunched" <?php if($candidacy["status"] === "relaunched") echo "selected" ?>>Relancée</option>
                            <option value="responded" <?php if($candidacy["status"] === "responded") echo "selected" ?>>Répondue</option>
                            <option value="refused" <?php if($candidacy["status"] === "refused") echo "selected" ?>>Refusée</option>
                            <option value="disable" <?php if($candidacy["status"] === "disable") echo "selected" ?>>Déactivée</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Infos supplémentaires</label>
                    <textarea name="infos" type="infos" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition" ><?= $candidacy['infos'] ?></textarea>
                </div>

                <hr class="border-slate-200 my-8">

                <?php $index = 1; ?>

                <?php foreach($candidacy["contacts"] as $contact): ?>
                    <div class="space-y-4 bg-slate-50 p-5 rounded-xl border border-slate-200 contact-block">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-indigo-600 uppercase tracking-wider">Contact <?= $index ?></span>
                            <a href="api/del_contact.php?contact_id=<?= $contact['id'] ?>&cand_id=<?= $candidacy['id'] ?>" class="hover:cursor-pointer">&#10060;</a>
                        </div>
                    
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nom</label>
                                <input class="bg-white w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600"
                                       type="text"
                                       value="<?= $contact['name'] ?>"
                                       name="contact_name_<?= $contact["id"] ?>"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                                <input class="bg-white w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600"
                                       type="email"
                                       value="<?= $contact['email'] ?>"
                                       name="contact_email_<?= $contact["id"] ?>"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tél</label>
                            <input class="bg-white w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600"
                                    type="phone"
                                    value="<?= $contact['phone'] !== '' ? $contact['phone'] : '' ?>"
                                    name="contact_phone_<?= $contact["id"] ?>"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">LinkedIn</label>
                            <input class="bg-white w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600"
                                    type="text"
                                    value="<?= $contact['linkedin'] !== '' ? $contact['linkedin'] : '' ?>"
                                    name="contact_linkedin_<?= $contact["id"] ?>"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Fonction</label>
                            <select name="contact_role_<?= $contact["id"] ?>" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition">
                                <option value="CTO" <?php if($contact["role"] === "CTO") echo "selected" ?>>CTO</option>
                                <option value="CEO" <?php if($contact["role"] === "CEO") echo "selected" ?>>CEO</option>
                                <option value="Lead dev" <?php if($contact["role"] === "Lead dev") echo "selected" ?>>Lead dev</option>
                                <option value="Tech lead" <?php if($contact["role"] === "Tech lead") echo "selected" ?>>Tech lead</option>
                                <option value="Fondateur" <?php if($contact["role"] === "Fondateur") echo "selected" ?>>Fondateur</option>
                                <option value="Chasseur IT" <?php if($contact["role"] === "Chasseur IT") echo "selected" ?>>Chasseur IT</option>
                                <option value="Responsable du recrutement" <?php if($contact["role"] === "Responsable du recrutement") echo "selected" ?>>Responsable du recrutement</option>
                                <option value="Développeur" <?php if($contact["role"] === "Développeur") echo "selected" ?>>Développeur</option>
                                <option value="Autre" <?php if($contact["role"] === "Autre") echo "selected" ?>>Autre</option>
                            </select>
                        </div>
                    </div>

                    <?php $index++; ?>
                <?php endforeach ?>

                <input name="id" value="<?= $_GET["id"] ?>" hidden />

                <button id="save_cand" class="w-full sm:flex-1 px-4 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition">
                    Mettre à jour la candidature
                </button>
            </form>

        </main>

        <footer class="py-6 text-center text-xs text-slate-400">
            Gestion de candidatures &bull; Tous droits réservés
        </footer>
    </div>

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