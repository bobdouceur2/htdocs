<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $intitule = $_POST['intitule'];
    $objectifs = $_POST['objectifs'];
    $datededebut = $_POST['datededebut'];
    $datedefin = $_POST['datedefin'];
    $avancement = $_POST['avancement'];
    $participants = $_POST['participants'];
    $levier = $_POST['levier'];

    // Vérifier les valeurs reçues
    error_log("ID: $id, Intitule: $intitule, Objectifs: $objectifs, DateDeDebut: $datededebut, DateDeFin: $datedefin, Avancement: $avancement, Participants: $participants, Levier: $levier");

    $sql = "UPDATE projets SET Intitule = ?, Objectifs = ?, DateDeDebut = ?, DateDeFin = ?, Avancement = ?, Participants = ?, Levier = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param("sssssssi", $intitule, $objectifs, $datededebut, $datedefin, $avancement, $participants, $levier, $id);

    if ($stmt->execute()) {
        echo "Ligne mise à jour avec succès.";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Méthode de requête incorrecte";
}

$conn->close();
?>