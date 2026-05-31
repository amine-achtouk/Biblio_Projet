<?php
require_once("../config/db.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_etd   = $_POST["id_etd"];
    $id_livre = $_POST["id_livre"];
    $date_pret = $_POST["date_pret"];
    $date_retour = date('Y-m-d', strtotime($date_pret . ' +15 days'));

    $sql = "INSERT INTO pret (NumEtd, NumLivre, DatePret, DateRetour) VALUES (?, ?, ?, ?)";
    $t = $cnx->prepare($sql);
    $t->execute([$id_etd, $id_livre, $date_pret, $date_retour]);
    header("Location: prets.php");
    exit();
}

$prets = $cnx->query("SELECT p.*, e.NomEtd, e.PrenomEdt, l.TitreLivre 
                       FROM pret p 
                       JOIN etudiant e ON p.NumEtd = e.NumEtd 
                       JOIN livre l ON p.NumLivre = l.NumLivre 
                       ORDER BY p.DatePret DESC")->fetchAll(PDO::FETCH_ASSOC);

$etudiants = $cnx->query("SELECT NumEtd, NomEtd, PrenomEdt FROM etudiant")->fetchAll(PDO::FETCH_ASSOC);
$livres    = $cnx->query("SELECT NumLivre, TitreLivre FROM livre")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prêts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap');
        * { font-family: 'Poppins', sans-serif; }
        .fancy { font-family: 'Playfair Display', serif; }
        .glass { background: rgba(17,24,39,0.7); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.08); }
        select option { background: #0f172a; color: white; }
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
            <a href="etudiants.php" class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-yellow-400 hover:bg-white/5 transition-all">🎓 Etudiants</a>
            <a href="prets.php"     class="px-4 py-3 rounded-xl text-sm font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">📖 Prêts</a>
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
                <h2 class="fancy text-3xl font-bold text-white mt-1">Les <span class="text-yellow-400">Prêts</span></h2>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                <?= count($prets) ?> prêt(s)
            </span>
        </div>

        <!-- FORM -->
        <div class="glass rounded-2xl p-6 mb-8 border border-white/5">
            <h3 class="text-lg font-bold text-white mb-6">➕ Ajouter un Prêt</h3>
            <form method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div>
                    <label class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Étudiant</label>
                    <select name="id_etd" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all">
                        <option value="" disabled selected>Choisir un étudiant</option>
                        <?php foreach($etudiants as $e): ?>
                            <option value="<?= $e['NumEtd'] ?>"><?= htmlspecialchars($e['NomEtd'] . ' ' . $e['PrenomEdt']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Livre</label>
                    <select name="id_livre" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all">
                        <option value="" disabled selected>Choisir un livre</option>
                        <?php foreach($livres as $l): ?>
                            <option value="<?= $l['NumLivre'] ?>"><?= htmlspecialchars($l['TitreLivre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Date de prêt</label>
                    <input type="date" name="date_pret" value="<?= date('Y-m-d') ?>" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all">
                </div>

                <div class="sm:col-span-3">
                    <button type="submit"
                        class="px-8 py-3 rounded-xl font-bold text-yellow-950 bg-gradient-to-r from-yellow-500 to-amber-400 hover:from-yellow-400 hover:to-amber-300 transition-all">
                        Ajouter le prêt
                    </button>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="glass rounded-2xl p-6 border border-white/5">
            <h3 class="text-lg font-bold text-white mb-4">📋 Liste des Prêts</h3>
            <div class="overflow-x-auto rounded-xl border border-white/5">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5 bg-slate-950/50">
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Étudiant</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Livre</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Date Prêt</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Date Retour</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach($prets as $p): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="p-4 text-sm text-white font-semibold"><?= htmlspecialchars($p['NomEtd'] . ' ' . $p['PrenomEdt']) ?></td>
                            <td class="p-4 text-sm text-slate-300"><?= htmlspecialchars($p['TitreLivre']) ?></td>
                            <td class="p-4 text-sm text-slate-400"><?= $p['DatePret'] ?></td>
                            <td class="p-4 text-sm">
                                <span class="px-3 py-1 rounded-md text-xs font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                    <?= $p['DateRetour'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($prets) == 0): ?>
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-500 text-sm">Aucun prêt pour le moment.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
</html>