<?php

session_start();

if(!isset($_SESSION["auth"]))
{
    header("location: auth/login.php");
    die();
}

?>

<!doctype html>
<html lang="fr" class="h-full bg-slate-50">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tableau de bord — Accueil</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <style>
        @media (max-width: 768px) {
            .fc .fc-toolbar {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                align-items: center;
            }
            .fc .fc-toolbar-title {
                font-size: 1.1rem !important;
            }
            .fc .fc-button {
                padding: 0.3rem 0.5rem !important;
                font-size: 0.8rem !important;
            }
        }
    </style>
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
            <h1 class="text-xl font-bold text-indigo-600">🚀 Dashboard Candidatures</h1>
            <div>
                <a href="auth/logout.php" class="inline-flex items-center px-3 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 transition">
                    Se déconnecter
                </a>
            </div>
        </div>
    </header>

  <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <sectionclass="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Calendrier des suivis</h2>
                <p class="text-sm text-slate-500 mt-1">Court-circuitez les filtres RH et ATS !</p>
            </div>
            <div class="flex flex-row gap-2">
                <a href="add_cand.php" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all">
                    + Enregistrer une nouvelle candidature
                </a>
                <a href="add_contacts.php" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-green-800 hover:bg-green-900 shadow-md hover:shadow-lg transition-all">
                    + Enregistrer un nouveau contact
                </a>
            </div>
        </div>

        <!-- Bloc Calendrier -->
        <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200">
            <!-- Wrapper responsive pour le calendrier -->
            <div id='calendar' class="w-full"></div>
        </div>
      </section>

  </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        Gestion de candidatures &bull; Tous droits réservés
    </footer>

  </body>
</html>

<script>

  document.addEventListener('DOMContentLoaded', async function()
  {
    let events = [];
  
    const res = await fetch("/api/get_all_cand.php");
    if(res.ok)
    {
      const cand = await res.json();

      cand.map((c) =>
      {
        const event =
        {
          title: c.status !== "disable" ? c.society : "N/A",
          start: c.date
        };

        if(c.status === "added")
        {
          event.backgroundColor = "#b4b4b4";

          const date = new Date();
          const paramsDate = c.date.split("-");
          date.setFullYear(paramsDate[0]);
          date.setMonth(paramsDate[1] - 1);
          date.setDate(paramsDate[2]);
        }

        else
        if(c.status === "treated")
        {
          event.backgroundColor = "#045318";

          const date = new Date();
          const paramsDate = c.date.split("-");
          date.setFullYear(paramsDate[0]);
          date.setMonth(paramsDate[1] - 1);
          date.setDate(paramsDate[2]);

          if(Date.now() > date.getTime() + 2 * 7 * 24 * 3600 * 1000)
          {
            event.backgroundColor = "#d37c0b";
          }
        }

        else
        if(c.status === "relaunched")
        {
          event.backgroundColor = "#0000ff";
        }

        else
        if(c.status === "refused")
        {
          event.backgroundColor = "#ff0000";
        }

        if(c.status === "disable")
        {
            event.backgroundColor = "#b4b4b4";
        }

        event.extendedProps = {id: c.id};
        
        events.push(event);
      });
    }
  
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth', // Vue par mois
      locale: 'fr', // Langue française
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },

      events,
      eventClick: async (info) =>
      {
        window.location = `edit_cand.php?id=${info.event.extendedProps.id}`;
      }
    });

    calendar.render();
  });

  /////////////////////////////////////////

  const okFlash = document.getElementById("ok_flash");

  if(okFlash)
  {
    document.getElementById("ok_flash").addEventListener("click", () =>
    {
      document.getElementById("flash").remove();
    });
  }

</script>