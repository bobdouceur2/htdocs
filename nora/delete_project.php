<?php
require_once 'db_connection.php'; // Connexion à la base de données

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête de suppression
    $sql = "DELETE FROM projets WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Projet supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression : " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID de projet non spécifié.";
}
?>
