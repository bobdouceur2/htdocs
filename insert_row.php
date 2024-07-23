<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intitule = $_POST['intitule'];
    $objectifs = $_POST['objectifs'];
    $datededebut = $_POST['datededebut'];
    $datedefin = $_POST['datedefin'];
    $avancement = $_POST['avancement'];
    $participants = $_POST['participants'];
    $levier = $_POST['levier'];

    $sql = "INSERT INTO projets (Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Participants, Levier) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $intitule, $objectifs, $datededebut, $datedefin, $avancement, $participants, $levier);

    if ($stmt->execute()) {
        echo "Ligne ajoutée avec succès.";
    } else {
        echo "Erreur: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Méthode de requête incorrecte";
}
?>
