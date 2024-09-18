<?php
require_once 'db_connection.php';

// Lire les données JSON envoyées via AJAX
$data = json_decode(file_get_contents('php://input'), true);

if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    exit();
}

$id = $data['id'];
$column = $data['column'];
$value = $data['value'];

// Sécuriser les données en échappant les caractères spéciaux pour éviter les injections SQL
$id = $conn->real_escape_string($id);
$column = $conn->real_escape_string($column);

// Si la valeur est vide, définir la colonne à NULL
if (trim($value) === '') {
    $query = "UPDATE projets SET `$column` = NULL WHERE ID = ?";
    $stmt = $conn->prepare($query);
} else {
    // Sinon, mettre à jour avec la valeur fournie
    $value = $conn->real_escape_string($value);
    $query = "UPDATE projets SET `$column` = ? WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $value, $id);
}

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'SQL error: ' . $conn->error]);
    exit();
}

// Exécuter la requête et vérifier si elle a réussi
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Modification réussie']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour : ' . $stmt->error]);
}
