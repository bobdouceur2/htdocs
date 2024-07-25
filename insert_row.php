<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intitule = $_POST['intitule'];
    $objectifs = $_POST['objectifs'];
    $datededebut = $_POST['datededebut'];
    $datedefin = $_POST['datedefin'];
    $avancement = $_POST['avancement'];
    $levier = $_POST['levier'];

    $sql = "INSERT INTO projets (Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $intitule, $objectifs, $datededebut, $datedefin, $avancement, $levier);
    if ($stmt->execute()) {
        require_once 'fetch_data.php'; // Afficher à nouveau le tableau mis à jour
    } else {
        echo "Erreur: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Méthode de requête incorrecte";
}
