<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialiser un tableau pour collecter les paramètres manquants
    $missing_params = [];

    // Récupérer les valeurs des paramètres POST, ajouter les paramètres manquants au tableau
    $intitule = isset($_POST['intitule']) ? $_POST['intitule'] : $missing_params[] = 'intitule';
    $objectifs = isset($_POST['objectifs']) ? $_POST['objectifs'] : $missing_params[] = 'objectifs';
    $datededebut = isset($_POST['datededebut']) ? $_POST['datededebut'] : $missing_params[] = 'datededebut';
    $datedefin = isset($_POST['datedefin']) ? $_POST['datedefin'] : $missing_params[] = 'datedefin';
    $avancement = isset($_POST['avancement']) ? $_POST['avancement'] : $missing_params[] = 'avancement';
    $participants = isset($_POST['participants']) ? $_POST['participants'] : $missing_params[] = 'participants';
    $levier = isset($_POST['levier']) ? $_POST['levier'] : $missing_params[] = 'levier';

    // Vérifier s'il y a des paramètres manquants
    if (!empty($missing_params)) {
        // Générer un message d'erreur détaillant les paramètres manquants
        echo "Les paramètres suivants sont manquants ou vides : " . implode(', ', $missing_params);
    } else {
        // Préparer la requête SQL pour insérer les données
        $sql = "INSERT INTO projets (Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Participants, Levier) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $intitule, $objectifs, $datededebut, $datedefin, $avancement, $participants, $levier);

        // Exécuter la requête et vérifier si elle a réussi
        if ($stmt->execute()) {
            echo "Ligne ajoutée avec succès.";
        } else {
            echo "Erreur: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "Méthode de requête incorrecte";
}

$conn->close();
?>
