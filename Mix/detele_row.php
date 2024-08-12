<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM projets WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "La ligne a été supprimée avec succès.";
    } else {
        echo "Erreur lors de la suppression : " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Aucun ID fourni ou méthode de requête incorrecte.";
}