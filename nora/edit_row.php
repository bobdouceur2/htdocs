<?php
// Affichage des erreurs sur le navigateur pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir un fichier de log pour error_log, au cas où le serveur n'est pas configuré pour afficher les logs
ini_set('error_log', '/chemin/vers/ton/fichier/log/php_error.log'); // Remplace ce chemin par un chemin valide sur ton serveur

require_once 'db_connection.php';

// Log de démarrage du script
error_log("Script edit_row.php démarré");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("Requête POST reçue");

    // Vérification et log des données POST reçues
    error_log("Données POST reçues: " . print_r($_POST, true));

    // Vérifier que tous les champs requis sont présents
    $required_fields = ['id', 'intitule', 'description_probleme', 'objectifs_operationnels', 'datededebut', 'datedefin', 'avancement', 'equipe'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) {
            error_log("Erreur: Le champ $field est manquant.");
            die("Erreur: Le champ $field est manquant.");
        }
    }

    // Récupération des données depuis le formulaire
    $id = $_POST['id'];
    $intitule = $_POST['intitule'] ?: null;
    $description_probleme = $_POST['description_probleme'] ?: null;
    $objectifs_operationnels = $_POST['objectifs_operationnels'] ?: null;
    $datededebut = $_POST['datededebut'] ?: null;
    $datedefin = $_POST['datedefin'] ?: null;
    $avancement = $_POST['avancement'] ?: null;
    $equipe = $_POST['equipe'] ?: null;
    $objectifs = !empty($_POST['objectifs']) ? $_POST['objectifs'] : null;
    $participants = !empty($_POST['participants']) ? $_POST['participants'] : null;
    $levier = !empty($_POST['levier']) ? $_POST['levier'] : null;
    $localisation = !empty($_POST['localisation']) ? $_POST['localisation'] : null;
    $perimetre = !empty($_POST['perimetre']) ? $_POST['perimetre'] : null;
    $planning = !empty($_POST['planning']) ? $_POST['planning'] : null;
    $dates_jalon_json = !empty($_POST['dates_jalon']) ? $_POST['dates_jalon'] : null;

    // Log des valeurs après traitement
    error_log("Valeurs traitées pour la requête: ID=$id, Intitulé=$intitule, DescriptionProblème=$description_probleme, ObjectifsOpérationnels=$objectifs_operationnels, DateDeDébut=$datededebut, DateDeFin=$datedefin, Avancement=$avancement, Équipe=$equipe, Objectifs=$objectifs, Participants=$participants, Levier=$levier, Localisation=$localisation, Perimetre=$perimetre, Planning=$planning, DatesJalonJSON=$dates_jalon_json");

    // Mise à jour de la table projets avec les nouvelles données, y compris les dates jalons
    error_log("Préparation de la requête SQL pour mettre à jour le projet.");
    $sql = "UPDATE projets SET Intitule = ?, DescriptionProbleme = ?, ObjectifsOperationnels = ?, DateDeDebut = ?, DateDeFin = ?, Avancement = ?, Equipe = ?, Objectifs = ?, Participants = ?, Levier = ?, Localisation = ?, Perimetre = ?, Planning = ?, dates_jalon = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Erreur de préparation de la requête: " . $conn->error);
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    // Vérification et liaison des paramètres
    error_log("Liaison des paramètres à la requête préparée.");
    if (!$stmt->bind_param(
        "ssssssssssssssi", 
        $intitule, 
        $description_probleme, 
        $objectifs_operationnels, 
        $datededebut, 
        $datedefin, 
        $avancement, 
        $equipe, 
        $objectifs, 
        $participants, 
        $levier, 
        $localisation, 
        $perimetre, 
        $planning, 
        $dates_jalon_json,
        $id
    )) {
        error_log("Erreur de liaison des paramètres: " . $stmt->error);
        die("Erreur de liaison des paramètres : " . $stmt->error);
    } else {
        error_log("Liaison des paramètres réussie.");
    }

    // Exécution de la requête et log du résultat
    error_log("Exécution de la requête SQL pour mettre à jour le projet.");
    if ($stmt->execute()) {
        error_log("Requête exécutée avec succès pour le projet ID $id.");
        echo "Ligne mise à jour avec succès.";
    } else {
        error_log("Erreur lors de l'exécution de la requête: " . $stmt->error);
        echo "Erreur lors de l'exécution de la requête : " . $stmt->error;
    }

    // Fermeture de la requête préparée
    error_log("Fermeture de la requête préparée.");
    $stmt->close();

} else {
    error_log("Méthode de requête incorrecte. Seules les requêtes POST sont acceptées.");
    echo "Méthode de requête incorrecte";
}

// Fermeture de la connexion à la base de données
error_log("Fermeture de la connexion à la base de données.");
$conn->close();
?>
