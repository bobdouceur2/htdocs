<?php
session_start();
require_once 'db_connection.php';

// Activer l'affichage des erreurs pour diagnostiquer le problème
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit();
}

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projet_id = isset($_POST['projet_id']) ? (int)$_POST['projet_id'] : null;
    $note = isset($_POST['note']) ? trim($_POST['note']) : '';

    // Vérifier que le projet est bien sélectionné
    if (!$projet_id) {
        die("Veuillez sélectionner un projet.");
    }

    // Vérifier si un fichier a bien été soumis
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $fileSize = $_FILES['file']['size']; // Obtenir la taille du fichier
        $fileContent = file_get_contents($fileTmpPath);

        // Débogage : Afficher les détails du fichier
        echo "File Size: " . $fileSize . "<br>";
        echo "File Name: " . $fileName . "<br>";

        // Vérifier si la taille du fichier est valide
        if ($fileSize === 0 || empty($fileSize)) {
            die("Erreur : La taille du fichier est invalide.");
        }

        // Insérer les informations du fichier dans la base de données
        $query = "INSERT INTO documents (name, type, size, content, note, projet_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Erreur lors de la préparation de la requête : " . $conn->error);
        }

        // Corriger la liaison des paramètres : i pour integer (taille du fichier)
        $stmt->bind_param("ssisss", $fileName, $fileType, $fileSize, $fileContent, $note, $projet_id);

        if ($stmt->execute()) {
            echo "Le fichier a été téléchargé et lié au projet.";
        } else {
            die("Erreur lors de l'insertion dans la base de données : " . $stmt->error);
        }
    } else {
        die("Erreur lors du téléchargement du fichier.");
    }
} else {
    die("Requête invalide.");
}
?>
