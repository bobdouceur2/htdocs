<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialiser un tableau pour collecter les paramètres manquants
    $missing_params = [];

    // Récupérer les valeurs des paramètres POST, ajouter les paramètres manquants au tableau
    $id = isset($_POST['id']) ? $_POST['id'] : $missing_params[] = 'id';
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
        // Vérifier si l'ID existe déjà dans la base de données
        $check_sql = "SELECT ID FROM projets WHERE ID = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "Erreur: Un projet avec cet ID existe déjà.";
        } else {
            // Préparer la requête SQL pour insérer les données avec un ID spécifié
            $sql = "INSERT INTO projets (ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Participants, Levier) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssss", $id, $intitule, $objectifs, $datededebut, $datedefin, $avancement, $participants, $levier);

            // Exécuter la requête et vérifier si elle a réussi
            if ($stmt->execute()) {
                echo "Ligne ajoutée avec succès.";
            } else {
                echo "Erreur: " . $stmt->error;
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
} else {
    echo "Méthode de requête incorrecte";
}

$conn->close();
?>
