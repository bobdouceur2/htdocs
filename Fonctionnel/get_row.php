<?php
// Inclure le fichier de connexion à la base de données
require_once 'db_connection.php';

// Vérifier si un identifiant (id) a été passé en paramètre dans l'URL
if (isset($_GET['id'])) {
    // Récupérer l'identifiant depuis les paramètres GET de l'URL
    $id = $_GET['id'];
    
    // Ajouter une vérification pour voir l'identifiant reçu
    error_log(" ID REÇU : " . $id);
    
    // Préparer une requête SQL pour sélectionner toutes les colonnes de la table 'projets' où l'identifiant correspond à celui fourni
    $sql = "SELECT * FROM projets WHERE id = ?";
    
    // Préparer la requête SQL pour éviter les injections SQL
    $stmt = $conn->prepare($sql);
    
    // Lier le paramètre de l'identifiant à la requête préparée
    $stmt->bind_param("i", $id);
    
    // Exécuter la requête SQL
    $stmt->execute();
    
    // Récupérer le résultat de la requête
    $result = $stmt->get_result();
    
    // Vérifier si le résultat contient au moins une ligne (le projet avec l'identifiant donné existe)
    if ($result->num_rows > 0) {
        // Encoder le résultat sous forme de JSON et l'envoyer comme réponse
        echo json_encode($result->fetch_assoc());
    } else {
        // Si aucun projet n'a été trouvé, renvoyer une erreur avec l'identifiant demandé
        echo json_encode(["error" => "Aucun projet trouvé avec l'ID = $id"]);
    }
    
    // Fermer la requête préparée
    $stmt->close();
} else {
    // Si aucun identifiant n'a été spécifié dans les paramètres GET, renvoyer une erreur
    echo json_encode(["error" => "ID non spécifié"]);
}

// Fermer la connexion à la base de données
$conn->close();
?>
