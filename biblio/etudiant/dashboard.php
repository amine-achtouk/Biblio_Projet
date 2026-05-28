<?php
require_once("../config/db.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Erreur : ID étudiant مفقود!");
}

$id = $_GET['id'];

$sql = "SELECT * FROM etudiant WHERE NumEtd = ?";
$t = $cnx->prepare($sql);
$t->execute([$id]);
$etd = $t->fetch(PDO::FETCH_ASSOC);

if (!$etd) {
    die("Étudiant introuvable!");
}

$sql2 = "SELECT p.*, l.TitreLivre FROM pret p JOIN livre l ON p.NumLivre = l.NumLivre WHERE p.NumEtd = ?";
$t2 = $cnx->prepare($sql2);
$t2->execute([$id]);
$prets = $t2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Prestige</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        .fancy-title {
            font-family: 'Playfair Display', serif;
        }

        .glass-dark {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="min-h-full bg-[#0a0f1e] text-slate-100 flex flex-col justify-between relative overflow-x-hidden">

    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -inset-[10px] opacity-10">
            <div class="absolute top-10 right-10 w-[500px] h-[500px] bg-yellow-600 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute bottom-10 left-10 w-[500px] h-[500px] bg-amber-700 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <div class="relative z-10 max-w-6xl w-full mx-auto px-4 py-8 flex-grow">
        
        <header class="glass-dark rounded-2xl p-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl border border-white/5">
            <div>
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest">Espace Membre</p>
                <h1 class="text-2xl font-bold text-white mt-1">
                    Bienvenue, <span class="text-yellow-400 fancy-title font-normal"><?= htmlspecialchars($etd['NomEtd'] . ' ' . $etd['PrenomEdt']) ?></span> ✨
                </h1>
            </div>
            
            <nav class="flex items-center gap-3">
                <a href="livres.php?id=<?= $id ?>" 
                   class="px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-sm font-semibold text-slate-300 hover:text-yellow-400 hover:border-yellow-500/30 transition-all duration-300">
                    📖 Voir les livres
                </a>
                <a href="profil.php?id=<?= $id ?>" 
                   class="px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-sm font-semibold text-slate-300 hover:text-yellow-400 hover:border-yellow-500/30 transition-all duration-300">
                    👤 Mon Profil
                </a>
                <a href="../index.php" 
                   class="px-4 py-2.5 rounded-xl bg-red-500/10 border border-red-500/20 text-sm font-semibold text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-lg shadow-red-950/20">
                    🚪 Déconnexion
                </a>
            </nav>
        </header>

        <main class="glass-dark rounded-3xl p-8 shadow-2xl border border-white/5">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-yellow-500/10 text-yellow-400">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0x" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white tracking-wide">Mes Prêts Actuels</h3>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                    <?= count($prets) ?> Livre(s)
                </span>
            </div>

            <?php if(count($prets) > 0): ?>
            <div class="overflow-x-auto rounded-xl border border-white/5 bg-slate-950/40">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/5 bg-slate-950/80">
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Titre du Livre</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Date d'Emprunt</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Date de Retour</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach($prets as $p): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors duration-150">
                            <td class="p-4 text-sm font-semibold text-white"><?= htmlspecialchars($p['TitreLivre']) ?></td>
                            <td class="p-4 text-sm text-slate-400"><?= htmlspecialchars($p['DatePret']) ?></td>
                            <td class="p-4 text-sm text-slate-400 font-medium">
                                <span class="inline-block px-2.5 py-1 rounded-md bg-slate-900 border border-white/5 text-xs">
                                    <?= htmlspecialchars($p['DateRetour']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="text-center py-12 bg-slate-950/30 rounded-xl border border-dashed border-white/5">
                    <p class="text-slate-500 text-sm font-medium">Aucun prêt enregistré pour le moment.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <footer class="relative z-10 text-center py-6 text-xs text-slate-600 border-t border-white/5 max-w-6xl w-full mx-auto px-4 mt-8">
        &copy; 2026 Prestige Library System. Tous droits réservés.
    </footer>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: { blob: "blob 12s infinite" },
                    keyframes: {
                        blob: {
                            "0%": { transform: "translate(0px, 0px) scale(1)" },
                            "50%": { transform: "translate(40px, -60px) scale(1.1)" },
                            "100%": { transform: "translate(0px, 0px) scale(1)" },
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>