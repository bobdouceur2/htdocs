<?php
require_once 'db_connection.php';

// Lire le flux d'entrée brut
$input = file_get_contents('php://input');

// Décoder le JSON en un tableau associatif
$data = json_decode($input, true);

// Vérifier si le décodage a réussi
if ($data === null) {
    echo 'Données JSON invalides';
    exit();
}

// Récupérer les données
$columnName = $data['columnName'];
$columnPosition = $data['columnPosition'];

// Vérifier que les données nécessaires sont présentes
if (empty($columnName) || empty($columnPosition)) {
    echo 'Nom de colonne ou position manquante';
    exit();
}

// Échapper les valeurs pour éviter les injections SQL
$columnName = $conn->real_escape_string($columnName);

// Déterminer la position où ajouter la colonne
$positionParts = explode(':', $columnPosition);
$position = $positionParts[0]; // 'before' ou 'after'
$referenceColumn = $conn->real_escape_string($positionParts[1]);

// Construire la requête SQL pour ajouter la colonne
$addColumnQuery = "ALTER TABLE projets ADD COLUMN `$columnName` VARCHAR(255) $position `$referenceColumn`";

if ($conn->query($addColumnQuery) === TRUE) {
    echo 'Colonne ajoutée avec succès';
} else {
    echo 'Erreur lors de l\'ajout de la colonne : ' . $conn->error;
}
?>
