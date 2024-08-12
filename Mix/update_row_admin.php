<?php
require_once 'db_connection.php'; // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous de valider et de nettoyer toutes les entrées
    $id = $_POST['id']; // ID de la ligne à modifier
    $intitule = $_POST['intitule'];
    $objectifs = $_POST['objectifs'];
    $datededebut = $_POST['datededebut'];
    $datedefin = $_POST['datedefin'];
    $avancement = $_POST['avancement'];
    
    // Ajouter d'autres variables pour les champs supplémentaires
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

    // Préparer la requête de mise à jour avec tous les champs nécessaires
    $sql = "UPDATE projets 
            SET Intitule = ?, Objectifs = ?, DateDeDebut = ?, DateDeFin = ?, Avancement = ?, Levier = ?, TrianglePCD = ?, Etape = ?, Transverse = ?, DI = ?, DT = ?, SFS = ?, DC = ?, Chantier = ?, Stream = ?
            WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssissssssssi", 
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
                      $stream,
                      $id);

    // Exécuter la requête et gérer les erreurs
    if ($stmt->execute()) {
        echo "La ligne a été modifiée avec succès.";
    } else {
        echo "Erreur lors de la modification : " . $stmt->error;
    }

    $stmt->close(); // Fermer le statement
} else {
    echo "Requête non autorisée.";
}

$conn->close(); // Fermer la connexion à la base de données
?>
