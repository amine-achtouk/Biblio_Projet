<?php
require_once("../config/db.php");

$nbLivres = $cnx->query("SELECT COUNT(*) FROM livre")->fetchColumn();
$nbEtudiants = $cnx->query("SELECT COUNT(*) FROM etudiant")->fetchColumn();
$nbPrets = $cnx->query("SELECT COUNT(*) FROM pret")->fetchColumn();
$nbDispo = $cnx->query("SELECT COUNT(*) FROM livre WHERE NumLivre NOT IN (SELECT NumLivre FROM pret WHERE DateRetour >= CURDATE())")->fetchColumn();

$sqlRecent = "SELECT e.NomEtd, e.PrenomEdt, l.TitreLivre, p.DatePret 
              FROM pret p 
              JOIN etudiant e ON p.NumEtd = e.NumEtd 
              JOIN livre l ON p.NumLivre = l.NumLivre 
              ORDER BY p.DatePret DESC LIMIT 5";
$recent = $cnx->query($sqlRecent)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap');
        * { font-family: 'Poppins', sans-serif; }
        .fancy { font-family: 'Playfair Display', serif; }
        .glass { background: rgba(17,24,39,0.7); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.08); }
    </style>
</head>
<body class="min-h-screen bg-[#0a0f1e] text-slate-100 flex">

    <!-- SIDEBAR -->
    <aside class="w-64 min-h-screen glass border-r border-white/5 flex flex-col py-8 px-4 fixed top-0 left-0">
        <h1 class="fancy text-2xl font-bold text-white mb-10 px-2">
            Biblio<span class="text-yellow-400">thèque</span>
        </h1>
        <nav class="flex flex-col gap-1">
            <a href="dashboard.php" class="px-4 py-3 rounded-xl text-sm font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">📊 Dashboard</a>
            <a href="livres.php"    class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">📚 Livres</a>
            <a href="auteurs.php"   class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">✍️ Auteurs</a>
            <a href="editeurs.php"  class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">🏢 Editeurs</a>
            <a href="themes.php"    class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">🏷️ Themes</a>
            <a href="etudiants.php" class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">🎓 Etudiants</a>
            <a href="prets.php"     class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">📖 Prêts</a>
        </nav>
        <div class="mt-auto">
            <a href="../index.php" class="px-4 py-3 rounded-xl text-sm font-semibold text-red-400 hover:bg-red-500/10 transition-all flex items-center gap-2">
                🚪 Déconnexion
            </a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="ml-64 flex-1 p-8">

        <!-- HEADER -->
        <div class="glass rounded-2xl p-6 mb-8 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest">Panneau de contrôle</p>
                <h2 class="fancy text-3xl font-bold text-white mt-1">Tableau de <span class="text-yellow-400">Bord</span></h2>
            </div>
            <span class="text-sm text-slate-400">Bienvenue, <span class="text-yellow-400 font-semibold">Admin</span></span>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass rounded-2xl p-6 border border-white/5">
                <p class="text-3xl font-bold text-yellow-400"><?= $nbLivres ?></p>
                <p class="text-sm text-slate-400 mt-1">📚 Livres</p>
            </div>
            <div class="glass rounded-2xl p-6 border border-white/5">
                <p class="text-3xl font-bold text-yellow-400"><?= $nbEtudiants ?></p>
                <p class="text-sm text-slate-400 mt-1">🎓 Etudiants</p>
            </div>
            <div class="glass rounded-2xl p-6 border border-white/5">
                <p class="text-3xl font-bold text-yellow-400"><?= $nbPrets ?></p>
                <p class="text-sm text-slate-400 mt-1">📖 Prêts</p>
            </div>
            <div class="glass rounded-2xl p-6 border border-white/5">
                <p class="text-3xl font-bold text-yellow-400"><?= $nbDispo ?></p>
                <p class="text-sm text-slate-400 mt-1">✅ Disponibles</p>
            </div>
        </div>

        <!-- ACTIVITES RECENTES -->
        <div class="glass rounded-2xl p-6 border border-white/5">
            <h3 class="text-lg font-bold text-white mb-4">🕐 Activités Récentes</h3>
            <?php if(count($recent) > 0): ?>
            <div class="overflow-x-auto rounded-xl border border-white/5">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5 bg-slate-950/50">
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Étudiant</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Livre</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach($recent as $r): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="p-4 text-sm text-white font-semibold"><?= htmlspecialchars($r['NomEtd'] . ' ' . $r['PrenomEdt']) ?></td>
                            <td class="p-4 text-sm text-slate-300"><?= htmlspecialchars($r['TitreLivre']) ?></td>
                            <td class="p-4 text-sm text-slate-400"><?= $r['DatePret'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p class="text-slate-500 text-sm text-center py-8">Aucune activité récente.</p>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>