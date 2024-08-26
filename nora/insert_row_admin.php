<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $intitule = $_POST['intitule'];
    $objectifs = $_POST['objectifs'];
    $datededebut = $_POST['datededebut'];
    $datedefin = $_POST['datedefin'];
    $avancement = $_POST['avancement'];
    $levier = $_POST['levier'];
    $trianglePCD = $_POST['trianglePCD'];
    $etape = $_POST['etape'];
    $transverse = $_POST['transverse'];
    $DI = $_POST['DI'];
    $DT = $_POST['DT'];
    $SFS = $_POST['SFS'];
    $DC = $_POST['DC'];
    $chantier = $_POST['chantier'];
    $stream = $_POST['Stream'];

    // Insérer des données dans la table 'projets_admin' avec de nouveaux champs
    $sql = "INSERT INTO projets_admin (
        Intitule,
        Objectifs,
        DateDeDebut,
        DateDeFin,
        Avancement,
        Levier,
        TrianglePCD,
        Etape,
        Transverse,
        DI,
        DT,
        SFS,
        DC,
        Chantier,
        Stream
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssss",
        $intitule,
        $objectifs,
        $datededebut,
        $datedefin,
        $avancement,
        $levier,
        $trianglePCD,
        $etape,
        $transverse,
        $DI,
        $DT,
        $SFS,
        $DC,
        $chantier,
        $stream
    );

    if ($stmt->execute()) {
        require_once 'fetch_data_admin.php'; // Afficher à nouveau le tableau mis à jour
    } else {
        echo "Erreur : " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Méthode de requête incorrecte";
}
