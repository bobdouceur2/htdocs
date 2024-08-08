<?php
session_start();

// Vérification de l'état de la session
if (isset($_SESSION['userId'])) {
    // Démarrage de la destruction de la session
    session_unset(); // Supprime toutes les variables de session
    session_destroy(); // Détruit la session
}

// Redirection vers la page d'accueil
header('Location: index.php');
exit();

