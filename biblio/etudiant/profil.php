<?php
require_once("../config/db.php");

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Erreur : ID étudiant مفقود!");
}

$id = $_GET["id"];
$message = "";

$sql = "SELECT * FROM etudiant WHERE NumEtd = ?";
$t = $cnx->prepare($sql);
$t->execute([$id]);
$etd = $t->fetch(PDO::FETCH_ASSOC);

if (!$etd) {
    die("Étudiant introuvable!");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $adresse = $_POST["adresse"];

    $sql_update = "UPDATE etudiant SET NomEtd = ?, PrenomEdt = ?, AdresseEtd = ? WHERE NumEtd = ?";
    $t_update = $cnx->prepare($sql_update);

    if ($t_update->execute([$nom, $prenom, $adresse, $id])) {
        $message = "Modification réussie !";
        $etd['NomEtd'] = $nom;
        $etd['PrenomEdt'] = $prenom;
        $etd['AdresseEtd'] = $adresse;
    } else {
        $message = "Erreur lors de la modification.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil Prestige</title>
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
            <div class="absolute top-10 right-1/4 w-[500px] h-[500px] bg-yellow-600 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute bottom-10 left-1/4 w-[500px] h-[500px] bg-amber-700 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <div class="relative z-10 max-w-4xl w-full mx-auto px-4 py-8 flex-grow">
        
        <header class="glass-dark rounded-2xl p-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl border border-white/5">
            <div>
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest">Gestion du Compte</p>
                <h1 class="text-2xl font-bold text-white mt-1 fancy-title tracking-wide">
                    Mon <span class="text-yellow-400">Profil</span>
                </h1>
            </div>
            
            <nav class="flex items-center gap-3">
                <a href="dashboard.php?id=<?= htmlspecialchars($id) ?>" 
                   class="px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-sm font-semibold text-slate-300 hover:text-yellow-400 hover:border-yellow-500/30 transition-all duration-300">
                    📊 Dashboard
                </a>
                <a href="../index.php" 
                   class="px-4 py-2.5 rounded-xl bg-red-500/10 border border-red-500/20 text-sm font-semibold text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-lg shadow-red-950/20">
                    🚪 Déconnexion
                </a>
            </nav>
        </header>

        <main class="glass-dark rounded-3xl p-8 sm:p-10 shadow-2xl border border-white/5 max-w-2xl mx-auto">
            
            <?php if (!empty($message)): ?>
                <div class="mb-6 p-4 rounded-xl text-sm font-semibold text-center border <?= strpos($message, 'réussie') !== false ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-red-500/10 border-red-500/30 text-red-400' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="profil.php?id=<?= htmlspecialchars($id) ?>" method="POST" class="space-y-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nom" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Nom</label>
                        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($etd['NomEtd']) ?>" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white font-medium focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
                    </div>
                    
                    <div>
                        <label for="prenom" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Prénom</label>
                        <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($etd['PrenomEdt']) ?>" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white font-medium focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label for="adresse" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Adresse</label>
                    <input type="text" name="adresse" id="adresse" value="<?= htmlspecialchars($etd['AdresseEtd']) ?>" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white font-medium focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full py-4 px-6 rounded-2xl text-yellow-950 font-extrabold text-lg bg-gradient-to-r from-yellow-500 via-amber-400 to-yellow-600 hover:from-yellow-400 hover:via-yellow-300 hover:to-amber-500 active:scale-[0.97] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0a0f1e] focus:ring-yellow-500 transition-all duration-300 shadow-[0_10px_30px_-5px_rgba(234,179,8,0.5)]">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
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
                            "50%": { transform: "translate(-40px, 30px) scale(1.05)" },
                            "100%": { transform: "translate(0px, 0px) scale(1)" },
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>