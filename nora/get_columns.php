<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

// Récupérer toutes les colonnes dynamiquement depuis la base de données
$columns_query = "SHOW COLUMNS FROM projets";
$columns_result = $conn->query($columns_query);

if (!$columns_result) {
    // En cas d'erreur SQL, retourner une erreur JSON
    echo json_encode(['error' => 'Erreur de récupération des colonnes']);
    exit();
}

$all_columns = [];
while ($row = $columns_result->fetch_assoc()) {
    $all_columns[] = $row['Field'];
}

// Récupérer les colonnes par défaut depuis la table default_columns
$default_columns_query = "SELECT column_name FROM default_columns";
$default_columns_result = $conn->query($default_columns_query);

if (!$default_columns_result) {
    // En cas d'erreur SQL, retourner une erreur JSON
    echo json_encode(['error' => 'Erreur de récupération des colonnes par défaut']);
    exit();
}

$default_columns = [];
while ($row = $default_columns_result->fetch_assoc()) {
    $default_columns[] = $row['column_name'];
}

// Retourner les colonnes et les colonnes par défaut sous forme de JSON
$response = [
    'all_columns' => $all_columns,
    'default_columns' => $default_columns
];

// Encodez la réponse en JSON et envoyez-la
echo json_encode($response);
?>
