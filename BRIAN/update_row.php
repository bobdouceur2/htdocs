<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $intitule = $_POST['intitule'];
    $objectifs = $_POST['objectifs'];
    $datededebut = $_POST['datededebut'];
    $datedefin = $_POST['datedefin'];
    $avancement = $_POST['avancement'];

    // Assurez-vous d'utiliser des requêtes préparées pour éviter les injections SQL
    $stmt = $conn->prepare("UPDATE projets SET intitule = ?, objectifs = ?, datededebut = ?, datedefin = ?, avancement = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $intitule, $objectifs, $datededebut, $datedefin, $avancement, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
}
?>
