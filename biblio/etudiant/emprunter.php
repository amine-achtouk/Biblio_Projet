<?php
require_once("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['id_etd']) || !isset($_POST['id_livre']) || !isset($_POST['date_pret'])) {
        die("Erreur : Données incomplètes !");
    }

    $id_etd = $_POST['id_etd'];
    $id_livre = $_POST['id_livre'];
    $date_pret = $_POST['date_pret']; 

    $date_retour = date('Y-m-d', strtotime($date_pret . ' +15 days')); 

    try {
        $check_sql = "SELECT * FROM pret WHERE NumEtd = ? AND NumLivre = ?";
        $check_stmt = $cnx->prepare($check_sql);
        $check_stmt->execute([$id_etd, $id_livre]);
        $already_borrowed = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($already_borrowed) {
            header("Location: livres.php?id=" . $id_etd . "&error=duplicate");
            exit();
        }

        $sql = "INSERT INTO pret (NumEtd, NumLivre, DatePret, DateRetour) VALUES (?, ?, ?, ?)";
        $t = $cnx->prepare($sql);
        $t->execute([$id_etd, $id_livre, $date_pret, $date_retour]);

        header("Location: livres.php?id=" . $id_etd . "&success=1");
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'emprunt : " . $e->getMessage());
    }
} else {
    die("Accès refusé.");
}
?>