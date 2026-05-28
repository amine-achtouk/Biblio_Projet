<?php
require_once("../config/db.php");

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Erreur : ID étudiant مفقود!");
}

$id = $_GET["id"];

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$sql = "SELECT l.*, a.NomAuteur, e.NomEditeur, t.IntituleTheme
        FROM livre l
        JOIN auteur a ON l.NumAuteur = a.NumAuteur
        JOIN editeur e ON l.NumEditeur = e.NumEditeur
        JOIN theme t ON l.NumTheme = t.NumTheme
        WHERE 1=1";

$params = [];

if($search !== '') {
    if($filter === 'auteur') {
        $sql .= " AND a.NomAuteur LIKE ?";
        $params[] = "%$search%";
    } elseif($filter === 'theme') {
        $sql .= " AND t.IntituleTheme LIKE ?";
        $params[] = "%$search%";
    } elseif($filter === 'editeur') {
        $sql .= " AND e.NomEditeur LIKE ?";
        $params[] = "%$search%";
    } else {
        $sql .= " AND (l.TitreLivre LIKE ? OR a.NomAuteur LIKE ? OR t.IntituleTheme LIKE ? OR e.NomEditeur LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
}

$t = $cnx->prepare($sql);
$t->execute($params);
$livres = $t->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque Prestige - Galerie des Livres</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap');
        * { font-family: 'Poppins', sans-serif; }
        .fancy-title { font-family: 'Playfair Display', serif; }
        .glass-dark {
            background: rgba(17, 24, 39, 0.65);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .book-card {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
        }
        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body class="min-h-full bg-[#0a0f1e] text-slate-100 flex flex-col justify-between relative overflow-x-hidden">

    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -inset-[10px] opacity-10">
            <div class="absolute top-20 right-10 w-[600px] h-[600px] bg-yellow-600 rounded-full filter blur-3xl"></div>
        </div>
    </div>

    <div class="relative z-10 max-w-7xl w-full mx-auto px-4 py-8 flex-grow">

        <!-- HEADER -->
        <header class="glass-dark rounded-2xl p-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl border border-white/5">
            <div>
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest font-mono">Collection de la Bibliothèque</p>
                <h1 class="text-3xl font-bold text-white mt-1 fancy-title tracking-wide">
                    Le Salon des <span class="text-yellow-400">Livres</span>
                </h1>
            </div>
            <nav class="flex items-center gap-3">
                <a href="dashboard.php?id=<?= htmlspecialchars($id) ?>" class="px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-sm font-semibold text-slate-300 hover:text-yellow-400 hover:border-yellow-500/30 transition-all duration-300">📊 Dashboard</a>
                <a href="mes_prets.php?id=<?= htmlspecialchars($id) ?>" class="px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-sm font-semibold text-slate-300 hover:text-yellow-400 hover:border-yellow-500/30 transition-all duration-300">📋 Mes Emprunts</a>
                <a href="../index.php" class="px-4 py-2.5 rounded-xl bg-red-500/10 border border-red-500/20 text-sm font-semibold text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300">🚪 Déconnexion</a>
            </nav>
        </header>

        <!-- MESSAGES -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="mb-6 p-4 rounded-xl text-sm font-semibold text-center bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                🎉 Le livre a été ajouté à vos emprunts avec succès.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate'): ?>
            <div class="mb-6 p-4 rounded-xl text-sm font-semibold text-center bg-red-500/10 border border-red-500/30 text-red-400 animate-pulse">
                ⚠️ Vous avez déjà emprunté ce livre !
            </div>
        <?php endif; ?>

        <!-- SEARCH BAR -->
        <form method="GET" action="" class="glass-dark rounded-2xl p-4 mb-8 border border-white/5">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <div class="flex flex-col sm:flex-row gap-3">
                <input 
                    type="text" 
                    name="search" 
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Rechercher un livre..." 
                    class="flex-1 px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all"
                >
                <select name="filter" class="px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-slate-300 focus:outline-none focus:ring-2 focus:ring-yellow-600 transition-all appearance-none">
                    <option value="all"    <?= $filter === 'all'     ? 'selected' : '' ?>>🔍 Tous</option>
                    <option value="auteur" <?= $filter === 'auteur'  ? 'selected' : '' ?>>✍️ Par Auteur</option>
                    <option value="theme"  <?= $filter === 'theme'   ? 'selected' : '' ?>>📂 Par Thème</option>
                    <option value="editeur"<?= $filter === 'editeur' ? 'selected' : '' ?>>🏢 Par Éditeur</option>
                </select>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-yellow-950 bg-gradient-to-r from-yellow-500 to-amber-400 hover:from-yellow-400 hover:to-amber-300 transition-all">
                    Rechercher
                </button>
                <?php if($search): ?>
                    <a href="livres.php?id=<?= htmlspecialchars($id) ?>" class="px-5 py-3 rounded-xl bg-slate-800 border border-white/5 text-slate-400 hover:text-white text-sm font-semibold transition-all text-center">
                        ✕ Effacer
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- RÉSULTATS COUNT -->
        <?php if($search): ?>
            <p class="text-slate-400 text-sm mb-6">
                <span class="text-yellow-400 font-bold"><?= count($livres) ?></span> résultat(s) pour 
                "<span class="text-white"><?= htmlspecialchars($search) ?></span>"
            </p>
        <?php endif; ?>

        <!-- LIVRES GRID -->
        <?php if(count($livres) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php foreach($livres as $l): ?>
                    <form action="emprunter.php" method="POST" 
                          onsubmit="return confirm('Voulez-vous emprunter « <?= addslashes(htmlspecialchars($l['TitreLivre'])) ?> » ?');"
                          class="book-card glass-dark rounded-2xl overflow-hidden flex flex-col justify-between border border-white/5 bg-gradient-to-b from-slate-900/40 to-slate-950/80">

                        <input type="hidden" name="id_etd"   value="<?= htmlspecialchars($id) ?>">
                        <input type="hidden" name="id_livre" value="<?= $l['NumLivre'] ?>">

                        <!-- PHOTO -->
                        <div class="relative group overflow-hidden h-72 bg-slate-950/50 flex items-center justify-center">
                            <img 
                                src="<?= $l['Photo'] ? '../get_photo.php?id=' . $l['NumLivre'] : 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=400&auto=format&fit=crop' ?>" 
                                alt="<?= htmlspecialchars($l['TitreLivre']) ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 group-hover:opacity-100"
                            >
                            <span class="absolute top-3 left-3 px-2.5 py-1 text-[10px] font-bold tracking-wider uppercase rounded-md bg-slate-950/80 text-yellow-400 border border-yellow-500/30 backdrop-blur-md">
                                <?= htmlspecialchars($l['IntituleTheme']) ?>
                            </span>
                        </div>

                        <!-- INFO -->
                        <div class="p-5 flex-grow flex flex-col justify-between gap-4">
                            <div>
                                <h3 class="text-base font-bold text-white tracking-wide line-clamp-2 min-h-[3rem]">
                                    <?= htmlspecialchars($l['TitreLivre']) ?>
                                </h3>
                                <p class="text-xs text-yellow-600/80 font-semibold mt-1">
                                    Par : <span class="text-slate-300"><?= htmlspecialchars($l['NomAuteur']) ?></span>
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <?= htmlspecialchars($l['NomEditeur']) ?> · <?= htmlspecialchars($l['AnneeEdition']) ?>
                                </p>
                            </div>

                            <div class="border-t border-white/5 pt-3">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Date d'emprunt souhaitée</label>
                                <input type="date" name="date_pret" required value="<?= date('Y-m-d') ?>"
                                       class="w-full px-3 py-2 text-xs rounded-lg bg-slate-950/80 border border-white/10 text-yellow-400 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all">
                            </div>

                            <button type="submit" class="w-full py-3 px-4 rounded-xl text-xs font-extrabold text-yellow-950 bg-gradient-to-r from-yellow-500 via-amber-400 to-yellow-600 hover:from-yellow-400 hover:to-amber-400 active:scale-95 transition-all duration-150 uppercase tracking-wider">
                                📖 Confirmer l'emprunt
                            </button>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="text-center py-20 glass-dark rounded-3xl border border-dashed border-white/10">
                <p class="text-slate-500 text-base font-medium">
                    <?= $search ? 'Aucun résultat trouvé pour votre recherche.' : 'Aucun ouvrage n\'est exposé pour le moment.' ?>
                </p>
            </div>
        <?php endif; ?>

    </div>

    <footer class="relative z-10 text-center py-6 text-xs text-slate-600 border-t border-white/5 max-w-7xl w-full mx-auto px-4 mt-12">
        &copy; <?= date('Y') ?> Prestige Library System. Tous droits réservés.
    </footer>

</body>
</html>