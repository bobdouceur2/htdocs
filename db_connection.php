<?php
// Définition du nom du serveur de base de données
$servername = "localhost";

// Définition du nom d'utilisateur de la base de données
$username = "root";

// Définition du mot de passe de l'utilisateur de la base de données
$password = "";

// Définition du nom de la base de données à laquelle se connecter
$dbname = "projets";

// Création d'une nouvelle connexion à la base de données MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification si la connexion a échoué
if ($conn->connect_error) {
    // Si la connexion échoue, arrêter l'exécution du script et afficher un message d'erreur
    die("Connection failed: " . $conn->connect_error);
}

// Note: Pas besoin de fermer la connexion ici, elle sera fermée à la fin des scripts qui l'utilisent.
?>
