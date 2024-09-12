<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php';

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

// Vérifier si la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lire les données JSON
    $data = json_decode(file_get_contents('php://input'), true);

    $column_name = $data['column_name'];
    $column_position = $data['column_position'];

    // Valider les entrées
    if (!empty($column_name) && !empty($column_position)) {
        // Préparer la requête SQL pour ajouter la colonne
        $sql = "ALTER TABLE projets ADD $column_name VARCHAR(255) AFTER $column_position";
        
        // Afficher la requête SQL pour le débogage
        var_dump($sql);
        
        // Exécuter la requête et vérifier les erreurs SQL
        if ($conn->query($sql) === TRUE) {
            echo "Nouvelle colonne ajoutée avec succès.";
        } else {
            // Afficher l'erreur SQL en cas de problème
            echo "Erreur lors de l'ajout de la colonne: " . $conn->error;
        }
    } else {
        echo "Veuillez entrer un nom de colonne et une position valide.";
    }
} else {
    echo "Méthode de requête incorrecte.";
}
?>
