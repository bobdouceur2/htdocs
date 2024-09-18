<?php
// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier que l'ID est présent
    if (!isset($_POST['ID'])) {
        die("Erreur: L'ID du projet est manquant.");
    }

    $id = $_POST['ID'];

    // Récupérer les noms de colonnes depuis la base de données
    $columns = [];
    $result = $conn->query("SHOW COLUMNS FROM projets");

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
    } else {
        die("Erreur lors de la récupération des colonnes : " . $conn->error);
    }

    // Colonnes à exclure
    $exclude_columns = ['ID']; // Ajouter d'autres colonnes à exclure si nécessaire

    // Colonnes à mettre à jour et données associées
    $update_columns = [];
    $update_data = [];

    foreach ($columns as $column) {
        if (!in_array($column, $exclude_columns) && isset($_POST[$column])) {
            $update_columns[] = $column;
            $update_data[$column] = $_POST[$column];
        }
    }

    if (empty($update_columns)) {
        die("Aucune donnée à mettre à jour.");
    }

    // Générer la clause SET
    $set_clause = implode(', ', array_map(function($col) {
        return "$col = ?";
    }, $update_columns));

    // Construire la requête SQL
    $sql = "UPDATE projets SET $set_clause WHERE ID = ?";

    // Préparer la requête
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    // Générer la chaîne des types
    $types = str_repeat('s', count($update_columns)) . 'i';

    // Récupérer les valeurs des paramètres
    $values = array_values($update_data);
    $values[] = $id;

    // Lier les paramètres
    $stmt->bind_param($types, ...$values);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Ligne mise à jour avec succès.";
    } else {
        echo "Erreur lors de l'exécution de la requête : " . $stmt->error;
    }

    // Fermer la requête
    $stmt->close();
} else {
    echo "Méthode de requête incorrecte";
}
