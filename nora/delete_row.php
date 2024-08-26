<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lire les données JSON de la requête
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Vérifier si les identifiants sont fournis
    if (isset($data['ids']) && is_array($data['ids'])) {
        // Préparer la requête SQL pour supprimer les lignes avec les identifiants fournis
        $ids = $data['ids'];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM projets WHERE ID IN ($placeholders)";
        
        $stmt = $conn->prepare($sql);
        
        // Lier les paramètres
        $types = str_repeat('i', count($ids));
        $stmt->bind_param($types, ...$ids);
        
        if ($stmt->execute()) {
            echo "Lignes supprimées avec succès.";
        } else {
            echo "Erreur: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Aucun identifiant fourni.";
    }
} else {
    echo "Méthode de requête incorrecte.";
}

