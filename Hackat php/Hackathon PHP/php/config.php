<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=Hackathon", "root", "");
    // Configuration de PDO pour qu'il génère des exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

?>
