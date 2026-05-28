<?php
require_once("../config/db.php");

$error_message = ""; 

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $adresse = $_POST["adresse"];
    $login = $_POST["login"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql0 = "SELECT * FROM etudiant WHERE Login = ? OR Email = ?";
    $t = $cnx->prepare($sql0);
    $t->execute([$login, $email]);
    $exist = $t->fetch(PDO::FETCH_ASSOC);

    if($exist){
        $error_message = "Login ou Email déjà utilisé";
    }
    else{
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql1 = "INSERT INTO etudiant (NomEtd, PrenomEdt, AdresseEtd, Login, MotDePasse, Email) VALUES (?, ?, ?, ?, ?, ?)";
        $t = $cnx->prepare($sql1);
        $t->execute([$nom, $prenom, $adresse, $login, $hash, $email]);
        header("Location: ../index.php" );
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Prestige - Bibliothèque</title>
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

        .gold-link {
            background: linear-gradient(to right, #f6e05e, #f6e05e);
            background-size: 0% 2px;
            background-repeat: no-repeat;
            background-position: left bottom;
            transition: background-size 0.3s ease;
        }
        .gold-link:hover {
            background-size: 100% 2px;
        }
    </style>
</head>
<body class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-x-hidden bg-[#0a0f1e]">

    <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="absolute -inset-[10px] opacity-20">
            <div class="absolute top-10 left-10 w-96 h-96 bg-amber-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-yellow-700 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-3000"></div>
        </div>
    </div>

    <div class="max-w-xl w-full space-y-8 glass-dark p-10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.6)] relative z-10 border border-white/5">
        
        <div class="text-center">
            <h2 class="fancy-title text-4xl font-bold tracking-tight text-white">
                Créer un Compte <span class="text-yellow-400">Membre</span>
            </h2>
            <p class="mt-3 text-sm text-slate-400 font-medium">
                Rejoignez notre espace de lecture prestige
            </p>
        </div>

        <?php if(!empty($error_message)): ?>
            <div class="bg-yellow-500/5 border border-yellow-600/30 text-yellow-300 text-sm p-3.5 rounded-xl text-center font-semibold animate-pulse shadow-sm shadow-yellow-900/20">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" method="POST">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="nom" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Nom</label>
                    <input type="text" name="nom" id="nom" required placeholder="Votre nom" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
                </div>
                
                <div>
                    <label for="prenom" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Prénom</label>
                    <input type="text" name="prenom" id="prenom" required placeholder="Votre prénom" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
                </div>
            </div>

            <div>
                <label for="adresse" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Adresse</label>
                <input type="text" name="adresse" id="adresse" required placeholder="Ex: 123 Rue de la Bibliothèque, Agadir" 
                    class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="login" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Identifiant (Login)</label>
                    <input type="text" name="login" id="login" required placeholder="Ex: amine_dev" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
                </div>
                
                <div>
                    <label for="email" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Adresse Email</label>
                    <input type="email" name="email" id="email" required placeholder="Ex: amine@gmail.com" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2">Mot de passe</label>
                <input type="password" name="password" id="password" required placeholder="••••••••" 
                    class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
            </div>

            <div class="pt-4">
                <button type="submit" 
                    class="w-full py-4 px-6 rounded-2xl text-yellow-950 font-extrabold text-lg bg-gradient-to-r from-yellow-500 via-amber-400 to-yellow-600 hover:from-yellow-400 hover:via-yellow-300 hover:to-amber-500 active:scale-[0.97] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0a0f1e] focus:ring-yellow-500 transition-all duration-300 shadow-[0_10px_30px_-5px_rgba(234,179,8,0.5)]">
                    S'inscrire
                </button>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-white/5 text-sm">
                <a href="../index.php" class="gold-link text-yellow-400 font-semibold pb-0.5">
                    Déjà membre ? Se connecter
                </a>
                <a href="../index.php" class="text-slate-400 hover:text-slate-300 transition-colors duration-200">
                    Retour à l'accueil
                </a>
            </div>
        </form>
    </div>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        blob: "blob 9s infinite",
                    },
                    keyframes: {
                        blob: {
                            "0%": { transform: "translate(0px, 0px) scale(1)" },
                            "50%": { transform: "translate(20px, -40px) scale(1.05)" },
                            "100%": { transform: "translate(0px, 0px) scale(1)" },
                        },
                    },
                },
            },
        }
    </script>
</body>
</html>