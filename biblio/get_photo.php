<?php
require_once("config/db.php");

$id = $_GET['id'] ?? 0;

$sql = "SELECT Photo FROM livre WHERE NumLivre = ?";
$t = $cnx->prepare($sql);
$t->execute([$id]);
$livre = $t->fetch(PDO::FETCH_ASSOC);

if($livre && $livre['Photo']) {
    header("Content-Type: image/jpeg");
    echo $livre['Photo'];
} else {
    header("Location: images/default_book.jpg");
}
?>