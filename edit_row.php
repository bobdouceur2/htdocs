<?php
// Inclure le fichier de connexion à la base de données
require_once 'db_connection.php';

// Vérifier si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données envoyées en POST
    $id = $_POST['id'];
    $intitule = $_POST['intitule'];
    $objectifs = $_POST['objectifs'];
    $datededebut = $_POST['datededebut'];
    $datedefin = $_POST['datedefin'];
    $avancement = $_POST['avancement'];
    $participants = $_POST['participants'];
    $levier = $_POST['levier'];

    // Vérifier les valeurs reçues (journalisation pour le débogage)
    error_log("ID: $id, Intitule: $intitule, Objectifs: $objectifs, DateDeDebut: $datededebut, DateDeFin: $datedefin, Avancement: $avancement, Participants: $participants, Levier: $levier");

    // Préparer la requête SQL pour mettre à jour la ligne correspondante
    $sql = "UPDATE projets SET Intitule = ?, Objectifs = ?, DateDeDebut = ?, DateDeFin = ?, Avancement = ?, Participants = ?, Levier = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Vérifier si la préparation de la requête a échoué
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    // Lier les paramètres à la requête préparée
    // 'sssssssi' indique les types des paramètres : s = string, i = integer
    $stmt->bind_param("sssssssi", $intitule, $objectifs, $datededebut, $datedefin, $avancement, $participants, $levier, $id);

    // Exécuter la requête et vérifier si elle a réussi
    if ($stmt->execute()) {
        echo "Ligne mise à jour avec succès.";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    // Fermer la requête préparée
    $stmt->close();
} else {
    // Si la méthode de requête n'est pas POST, afficher un message d'erreur
    echo "Méthode de requête incorrecte";
}

// Fermer la connexion à la base de données
$conn->close();
?>
