<?php
require_once("../config/db.php");

if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
    $id = $_GET['id'];
    $sql = "DELETE FROM etudiant WHERE NumEtd = ?";
    $t = $cnx->prepare($sql);
    $t->execute([$id]);
    header("Location: etudiants.php");
    exit();
}

$etudiants = $cnx->query("SELECT * FROM etudiant")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etudiants</title>
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
            <a href="dashboard.php" class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">📊 Dashboard</a>
            <a href="livres.php"    class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">📚 Livres</a>
            <a href="auteurs.php"   class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">✍️ Auteurs</a>
            <a href="editeurs.php"  class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">🏢 Editeurs</a>
            <a href="themes.php"    class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">🏷️ Themes</a>
            <a href="etudiants.php" class="px-4 py-3 rounded-xl text-sm font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">🎓 Etudiants</a>
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
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest">Gestion</p>
                <h2 class="fancy text-3xl font-bold text-white mt-1">Les <span class="text-yellow-400">Étudiants</span></h2>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                <?= count($etudiants) ?> étudiant(s)
            </span>
        </div>

        <!-- TABLE -->
        <div class="glass rounded-2xl p-6 border border-white/5">
            <div class="overflow-x-auto rounded-xl border border-white/5">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5 bg-slate-950/50">
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Num</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Nom</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Prénom</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Adresse</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Login</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Email</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach($etudiants as $e): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="p-4 text-sm text-slate-400"><?= $e['NumEtd'] ?></td>
                            <td class="p-4 text-sm text-white font-semibold"><?= htmlspecialchars($e['NomEtd']) ?></td>
                            <td class="p-4 text-sm text-slate-300"><?= htmlspecialchars($e['PrenomEdt']) ?></td>
                            <td class="p-4 text-sm text-slate-300"><?= htmlspecialchars($e['AdresseEtd']) ?></td>
                            <td class="p-4 text-sm text-slate-300"><?= htmlspecialchars($e['Login']) ?></td>
                            <td class="p-4 text-sm text-slate-300"><?= htmlspecialchars($e['Email']) ?></td>
                            <td class="p-4">
                                <a href="etudiants.php?action=supprimer&id=<?= $e['NumEtd'] ?>"
                                   onclick="return confirm('Supprimer cet étudiant ?')"
                                   class="px-3 py-1.5 rounded-lg text-xs font-bold text-red-400 bg-red-500/10 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all">
                                   🗑️ Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($etudiants) == 0): ?>
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500 text-sm">Aucun étudiant pour le moment.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
</html>