<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $datedefin = $_POST['datedefin'];
    $avancement = $_POST['avancement'];

    // Préparez et exécutez la mise à jour.
    $sql = "UPDATE projets SET DateDeFin = ?, Avancement = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $datedefin, $avancement, $id);

    if ($stmt->execute()) {
        // Si la mise à jour réussit, renvoyez une réponse vide ou un message JSON
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Erreur lors de la modification : " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Requête non autorisée."]);
}

$conn->close();
?>
