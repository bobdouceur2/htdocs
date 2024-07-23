<?php
require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Commencer une transaction
    $conn->begin_transaction();

    try {
        // Supprimer la ligne spécifiée
        $sql = "DELETE FROM projets WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Erreur de suppression : " . $stmt->error);
        }
        $stmt->close();

        // Mettre à jour les IDs des lignes restantes
        $sql = "UPDATE projets SET id = id - 1 WHERE id > ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Erreur de mise à jour des IDs : " . $stmt->error);
        }
        $stmt->close();

        // Valider la transaction
        $conn->commit();
        echo "Ligne supprimée et IDs mis à jour avec succès.";

    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $conn->rollback();
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "ID non spécifié.";
}

$conn->close();
?>
