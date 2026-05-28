<?php
require_once("../config/db.php");

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Erreur : ID étudiant مفقود!");
}

$id = $_GET["id"];

$sql = "SELECT p.DatePret, p.DateRetour, l.TitreLivre, a.NomAuteur 
        FROM pret p
        JOIN livre l ON p.NumLivre = l.NumLivre
        JOIN auteur a ON l.NumAuteur = a.NumAuteur
        WHERE p.NumEtd = ? 
        ORDER BY p.DatePret DESC";

$t = $cnx->prepare($sql);
$t->execute([$id]);
$mes_prets = $t->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Emprunts Prestige</title>
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
            <div class="absolute bottom-10 right-10 w-[500px] h-[500px] bg-yellow-600 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute top-10 left-10 w-[500px] h-[500px] bg-amber-700 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <div class="relative z-10 max-w-6xl w-full mx-auto px-4 py-8 flex-grow">
        
        <header class="glass-dark rounded-2xl p-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl border border-white/5">
            <div>
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest">Historique Personnel</p>
                <h1 class="text-2xl font-bold text-white mt-1 fancy-title tracking-wide">
                    Mes Livres <span class="text-yellow-400">Empruntés</span>
                </h1>
            </div>
            
            <nav class="flex items-center gap-3">
                <a href="dashboard.php?id=<?= htmlspecialchars($id) ?>" 
                   class="px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-sm font-semibold text-slate-300 hover:text-yellow-400 hover:border-yellow-500/30 transition-all duration-300">
                    📊 Dashboard
                </a>
                <a href="livres.php?id=<?= htmlspecialchars($id) ?>" 
                   class="px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-sm font-semibold text-slate-300 hover:text-yellow-400 hover:border-yellow-500/30 transition-all duration-300">
                    📖 Catalogue
                </a>
                <a href="profil.php?id=<?= htmlspecialchars($id) ?>" 
                   class="px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-sm font-semibold text-slate-300 hover:text-yellow-400 hover:border-yellow-500/30 transition-all duration-300">
                    👤 Profil
                </a>
                <a href="../index.php" 
                   class="px-4 py-2.5 rounded-xl bg-red-500/10 border border-red-500/20 text-sm font-semibold text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-lg shadow-red-950/20">
                    🚪 Déconnexion
                </a>
            </nav>
        </header>

        <main class="glass-dark rounded-3xl p-8 shadow-2xl border border-white/5">
            
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/5">
                <div class="p-2 rounded-lg bg-yellow-500/10 text-yellow-400">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white tracking-wide">Liste de vos transactions</h3>
            </div>

            <?php if (count($mes_prets) > 0): ?>
            <div class="overflow-x-auto rounded-xl border border-white/5 bg-slate-950/40">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/5 bg-slate-950/80">
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Titre du Livre</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Auteur</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Date de Prêt</th>
                            <th class="p-4 text-xs font-bold text-yellow-600 uppercase tracking-widest">Retour Prévue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach($mes_prets as $p): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors duration-150">
                            <td class="p-4 text-sm font-semibold text-white tracking-wide"><?= htmlspecialchars($p['TitreLivre']) ?></td>
                            <td class="p-4 text-sm text-slate-300"><?= htmlspecialchars($p['NomAuteur']) ?></td>
                            <td class="p-4 text-sm text-slate-400"><?= htmlspecialchars($p['DatePret']) ?></td>
                            <td class="p-4 text-sm">
                                <span class="inline-block px-3 py-1 rounded-md bg-yellow-500/5 border border-yellow-500/20 text-yellow-400 text-xs font-semibold tracking-wider">
                                    <?= htmlspecialchars($p['DateRetour']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="text-center py-16 bg-slate-950/30 rounded-xl border border-dashed border-white/5">
                    <p class="text-slate-500 text-sm font-medium">Vous n'avez aucun livre emprunté pour le moment.</p>
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
                            "50%": { transform: "translate(30px, -40px) scale(1.05)" },
                            "100%": { transform: "translate(0px, 0px) scale(1)" },
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>