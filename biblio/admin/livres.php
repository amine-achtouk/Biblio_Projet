<?php
require_once("../config/db.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $titre   = $_POST["titre"];
    $auteur  = $_POST["auteur"];
    $editeur = $_POST["editeur"];
    $theme   = $_POST["theme"];
    $annee   = $_POST["annee"];
    $photo = null;

    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
        $photo = file_get_contents($_FILES['photo']['tmp_name']);
    }
    $sql = "INSERT INTO livre (TitreLivre, NumAuteur, NumEditeur, NumTheme, AnneeEdition, Photo) VALUES (?, ?, ?, ?, ?, ?)";
    $t = $cnx->prepare($sql);
    $t->execute([$titre, $auteur, $editeur, $theme, $annee, $photo]);
    header("Location: livres.php");
    exit();
}

$livres   = $cnx->query("SELECT l.*, a.NomAuteur, e.NomEditeur, t.IntituleTheme 
                          FROM livre l 
                          JOIN auteur a ON l.NumAuteur = a.NumAuteur 
                          JOIN editeur e ON l.NumEditeur = e.NumEditeur 
                          JOIN theme t ON l.NumTheme = t.NumTheme")->fetchAll(PDO::FETCH_ASSOC);
$auteurs  = $cnx->query("SELECT * FROM auteur")->fetchAll(PDO::FETCH_ASSOC);
$editeurs = $cnx->query("SELECT * FROM editeur")->fetchAll(PDO::FETCH_ASSOC);
$themes   = $cnx->query("SELECT * FROM theme")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livres</title>
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
            <a href="livres.php"    class="px-4 py-3 rounded-xl text-sm font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">📚 Livres</a>
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
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest">Gestion</p>
                <h2 class="fancy text-3xl font-bold text-white mt-1">Les <span class="text-yellow-400">Livres</span></h2>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                <?= count($livres) ?> livre(s)
            </span>
        </div>

        <!-- FORM -->
        <div class="glass rounded-2xl p-6 mb-8 border border-white/5">
            <h3 class="text-lg font-bold text-white mb-6">➕ Ajouter un Livre</h3>
            <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Titre</label>
                    <input type="text" name="titre" required placeholder="Titre du livre"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Auteur</label>
                    <select name="auteur" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all">
                        <option value="" disabled selected>Choisir un auteur</option>
                        <?php foreach($auteurs as $a): ?>
                            <option value="<?= $a['NumAuteur'] ?>"><?= htmlspecialchars($a['NomAuteur']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Éditeur</label>
                    <select name="editeur" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all">
                        <option value="" disabled selected>Choisir un éditeur</option>
                        <?php foreach($editeurs as $e): ?>
                            <option value="<?= $e['NumEditeur'] ?>"><?= htmlspecialchars($e['NomEditeur']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Thème</label>
                    <select name="theme" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all">
                        <option value="" disabled selected>Choisir un thème</option>
                        <?php foreach($themes as $th): ?>
                            <option value="<?= $th['NumTheme'] ?>"><?= htmlspecialchars($th['IntituleTheme']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Année</label>
                    <input type="number" name="annee" min="1900" max="2099" required placeholder="2024"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Photo</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-slate-400 file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:bg-yellow-500/20 file:text-yellow-400 file:font-semibold hover:file:bg-yellow-500/30 transition-all">
                </div>

                <div class="sm:col-span-2">
                    <button type="submit"
                        class="px-8 py-3 rounded-xl font-bold text-yellow-950 bg-gradient-to-r from-yellow-500 to-amber-400 hover:from-yellow-400 hover:to-amber-300 transition-all">
                        Ajouter le livre
                    </button>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="glass rounded-2xl p-6 border border-white/5">
            <h3 class="text-lg font-bold text-white mb-4">📋 Liste des Livres</h3>
            <div class="overflow-x-auto rounded-xl border border-white/5">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5 bg-slate-950/50">
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Photo</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Titre</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Auteur</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Éditeur</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Thème</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Année</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach($livres as $l): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="p-4">
                                <?php if($l['Photo']): ?>
                                    <img src="../get_photo.php?id=<?= $l['NumLivre'] ?>" class="w-12 h-16 object-cover rounded-lg">
                                <?php else: ?>
                                    <div class="w-12 h-16 rounded-lg bg-slate-800 flex items-center justify-center text-slate-500 text-xs">N/A</div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-sm text-white font-semibold"><?= htmlspecialchars($l['TitreLivre']) ?></td>
                            <td class="p-4 text-sm text-slate-300"><?= htmlspecialchars($l['NomAuteur']) ?></td>
                            <td class="p-4 text-sm text-slate-300"><?= htmlspecialchars($l['NomEditeur']) ?></td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-md text-xs font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                    <?= htmlspecialchars($l['IntituleTheme']) ?>
                                </span>
                            </td>
                            <td class="p-4 text-sm text-slate-400"><?= $l['AnneeEdition'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($livres) == 0): ?>
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 text-sm">Aucun livre pour le moment.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
</html>