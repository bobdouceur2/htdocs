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
    // Lire les données JSON envoyées par le client
    $data = json_decode(file_get_contents('php://input'), true);

    $column_name = $data['columnName'];

    // Valider l'entrée pour s'assurer que la colonne est spécifiée
    if (!empty($column_name)) {
        // Préparer la requête SQL pour supprimer la colonne
        $sql = "ALTER TABLE projets DROP COLUMN $column_name";
        
        // Exécuter la requête et vérifier les erreurs SQL
        if ($conn->query($sql) === TRUE) {
            echo "Colonne supprimée avec succès.";
        } else {
            echo "Erreur lors de la suppression de la colonne: " . $conn->error;
        }
    } else {
        echo "Veuillez sélectionner une colonne valide.";
    }
} else {
    echo "Méthode de requête incorrecte.";
}
?>
