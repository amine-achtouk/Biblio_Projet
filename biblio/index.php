<?php
require_once("./config/db.php");

$error_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $role = $_POST["role"];
    $login = $_POST["login"];
    $psw = $_POST["password"];

    if($role == "admin"){
        $sql = "SELECT * FROM admin WHERE Login = ?";
        $t = $cnx -> prepare($sql);
        $t -> execute([$login]);
        $user = $t -> fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($psw, $user["MotDePasse"])){
            header("Location: ./admin/dashboard.php");
            exit();
        }else{
            $error_message = "Login ou mot de passe incorrect";
        }
    }
    if($role == "etudiant"){
        $sql = "SELECT * FROM etudiant WHERE Login = ?";
        $t = $cnx -> prepare($sql);
        $t -> execute([$login]);
        $user = $t -> fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($psw, $user["MotDePasse"])){
            header("Location: ./etudiant/dashboard.php?id=" . $user['NumEtd']);
            exit();
        }else{
            $error_message = "Login ou mot de passe incorrect";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Prestige - Bibliothèque</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* إضافة خط فخم من Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }

        .fancy-title {
            font-family: 'Playfair Display', serif;
        }

        /* تأثير الزجاج الداكن المخصص */
        .glass-dark {
            background: rgba(17, 24, 39, 0.7); /* bg-slate-900 with opacity */
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* تخصيص تأثير الـ Hover على الرابط الذهبي */
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
<body class="h-full flex items-center justify-center px-4 sm:px-6 lg:px-8 relative overflow-hidden">

    <div class="absolute inset-0 z-0 bg-[#0a0f1e]">
        <div class="absolute -inset-[10px] opacity-20">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-yellow-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-amber-700 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-1/4 left-1/2 w-96 h-96 bg-yellow-800 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <div class="max-w-md w-full space-y-8 glass-dark p-10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] relative z-10 border border-white/5">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-yellow-950 border border-yellow-700/50 mb-4 shadow-inner shadow-yellow-700/20">
                <svg class="h-9 w-9 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h2 class="mt-2 fancy-title text-4xl font-bold tracking-tight text-white">
                Prestige <span class="text-yellow-400">Library</span>
            </h2>
            <p class="mt-3 text-sm text-slate-400 font-medium">
                Accédez à l'excellence. Connectez-vous.
            </p>
        </div>

        <?php if(!empty($error_message)): ?>
            <div class="bg-yellow-500/5 border border-yellow-600/30 text-yellow-300 text-sm p-3.5 rounded-xl text-center font-semibold animate-pulse shadow-sm shadow-yellow-900/20">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-7" method="POST">
            
            <div>
                <label for="role" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2.5">Votre Privilège</label>
                <div class="relative">
                    <select name="role" id="role" required 
                        class="w-full px-5 py-4 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300 appearance-none">
                        <option value="" disabled selected>Sélectionner votre rôle</option>
                        <option value="etudiant">Étudiant Membre</option>
                        <option value="admin">Administrateur</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-yellow-600">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label for="login" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2.5">Identifiant Unique</label>
                <input type="text" name="login" id="login" required placeholder="Ex: membre_vip" 
                    class="w-full px-5 py-4 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-yellow-600 uppercase tracking-widest mb-2.5">Clef d'Accès</label>
                <input type="password" name="password" id="password" required placeholder="••••••••" 
                    class="w-full px-5 py-4 rounded-xl bg-slate-950/60 border border-white/5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition-all duration-300">
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-4 px-6 rounded-2xl text-yellow-950 font-extrabold text-lg bg-gradient-to-r from-yellow-500 via-amber-400 to-yellow-600 hover:from-yellow-400 hover:via-yellow-300 hover:to-amber-500 active:scale-[0.97] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0a0f1e] focus:ring-yellow-500 transition-all duration-300 shadow-[0_10px_30px_-5px_rgba(234,179,8,0.5)]">
                    Authentification
                </button>
            </div>

            <div class="text-center mt-6 pt-2 border-t border-white/5">
                <a href="./etudiant/inscription.php" class="gold-link text-sm font-semibold text-yellow-400 pb-1">
                    Nouveau membre ? Créer votre accès privilège
                </a>
            </div>
        </form>
    </div>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        blob: "blob 7s infinite",
                    },
                    keyframes: {
                        blob: {
                            "0%": {
                                transform: "translate(0px, 0px) scale(1)",
                            },
                            "33%": {
                                transform: "translate(30px, -50px) scale(1.1)",
                            },
                            "66%": {
                                transform: "translate(-20px, 20px) scale(0.9)",
                            },
                            "100%": {
                                transform: "translate(0px, 0px) scale(1)",
                            },
                        },
                    },
                },
            },
        }
    </script>
</body>
</html>