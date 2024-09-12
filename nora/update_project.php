<?php
// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php'; // Connexion à la base de données

// Lire les données JSON envoyées par la requête
$data = json_decode(file_get_contents('php://input'), true);

// Log pour vérifier les données reçues
error_log("Données reçues : " . print_r($data, true));

if (isset($data['id'])) {
    $id = $data['id'];

    // Préparer dynamiquement la requête de mise à jour en fonction des colonnes
    $set_clauses = [];
    $params = [];
    $types = "";

    // Parcourir les colonnes et les valeurs envoyées dans la requête JSON
    foreach ($data as $column => $value) {
        // Ne pas inclure l'ID dans les colonnes à mettre à jour
        if ($column != 'id') {
            if (in_array($column, ['DateDeDebut', 'DateDeFin'])) {
                // Vérifier et formater la date si c'est une colonne de type date
                $date = DateTime::createFromFormat('Y-m-d', $value);
                if ($date) {
                    $value = $date->format('Y-m-d'); // Formater la date au bon format pour MySQL
                    $types .= "s"; // Les dates sont traitées comme des chaînes
                } else {
                    error_log("Erreur de format de date pour la colonne $column : " . $value);
                    die("Erreur de format de date pour la colonne $column : " . $value);
                }
            } else {
                // Gérer les autres colonnes
                if (is_int($value) || preg_match('/\d+/', $value)) {
                    $value = ($value === '') ? 0 : $value;
                    $types .= "i"; // Pour les entiers
                } elseif (is_float($value)) {
                    $types .= "d"; // Pour les décimaux
                } else {
                    $value = ($value === '') ? NULL : $value; // Remplacez les chaînes vides par NULL pour les chaînes
                    $types .= "s"; // Par défaut, chaînes de caractères
                }
            }
            $set_clauses[] = "$column = ?";
            $params[] = $value;
        }
    }

    // Ajouter l'ID en tant que paramètre de condition
    $params[] = $id;
    $types .= "i"; // L'ID est un entier

    // Créer la requête SQL
    $sql = "UPDATE projets SET " . implode(", ", $set_clauses) . " WHERE ID = ?";

    // Log de la requête SQL et des paramètres
    error_log("Requête SQL : " . $sql);
    error_log("Paramètres liés : " . print_r($params, true));

    // Préparer la requête SQL
    $stmt = $conn->prepare($sql);

    // Vérifier si la préparation de la requête a échoué
    if ($stmt === false) {
        error_log("Erreur de préparation de la requête: " . $conn->error);
        die("Erreur de préparation de la requête: " . $conn->error);
    }

    // Lier les paramètres à la requête préparée
    if (!$stmt->bind_param($types, ...$params)) {
        error_log("Erreur de liaison des paramètres: " . $stmt->error);
        die("Erreur de liaison des paramètres: " . $stmt->error);
    }

    // Exécuter la requête
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Projet mis à jour avec succès.";
        } else {
            echo "Aucun changement détecté ou mise à jour échouée.";
        }
    } else {
        error_log("Erreur lors de l'exécution de la requête: " . $stmt->error);
        echo "Erreur lors de l'exécution de la requête : " . $stmt->error;
    }

    // Fermer la requête préparée
    $stmt->close();
} else {
    error_log("Aucun ID reçu dans la requête.");
    echo "Données invalides : l'ID est manquant.";
}
?>
