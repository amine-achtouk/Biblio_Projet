<?php
require_once("../config/db.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $intitule = $_POST["intitule"];
    $sql = "INSERT INTO theme (IntituleTheme) VALUES (?)";
    $t = $cnx->prepare($sql);
    $t->execute([$intitule]);
    header("Location: themes.php");
    exit();
}
$themes = $cnx->query("SELECT * FROM theme")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Themes</title>
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
            <a href="themes.php"    class="px-4 py-3 rounded-xl text-sm font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">🏷️ Themes</a>
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
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest">Gestion</p>
                <h2 class="fancy text-3xl font-bold text-white mt-1">Les <span class="text-yellow-400">Thèmes</span></h2>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                <?= count($themes) ?> thème(s)
            </span>
        </div>

        <!-- FORM -->
        <div class="glass rounded-2xl p-6 mb-8 border border-white/5">
            <h3 class="text-lg font-bold text-white mb-4">➕ Ajouter un Thème</h3>
            <form method="POST" class="flex gap-4">
                <input type="text" name="intitule" required placeholder="Intitulé du thème"
                    class="flex-1 px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all">
                <button type="submit"
                    class="px-6 py-3 rounded-xl font-bold text-yellow-950 bg-gradient-to-r from-yellow-500 to-amber-400 hover:from-yellow-400 hover:to-amber-300 transition-all">
                    Ajouter
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="glass rounded-2xl p-6 border border-white/5">
            <h3 class="text-lg font-bold text-white mb-4">📋 Liste des Thèmes</h3>
            <div class="overflow-x-auto rounded-xl border border-white/5">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5 bg-slate-950/50">
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Num</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Intitulé</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach($themes as $th): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="p-4 text-sm text-slate-400"><?= $th['NumTheme'] ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-md text-xs font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                    <?= htmlspecialchars($th['IntituleTheme']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($themes) == 0): ?>
                        <tr>
                            <td colspan="2" class="p-8 text-center text-slate-500 text-sm">Aucun thème pour le moment.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
</html>