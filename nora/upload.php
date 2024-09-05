<?php
require_once 'db_connection.php'; // Inclure votre fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        // Récupérer les détails du fichier
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $fileSize = $_FILES['file']['size'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $note = isset($_POST['note']) ? $_POST['note'] : ''; // Récupérer la note

        // Ouvrir le fichier et lire son contenu
        $fileContent = file_get_contents($fileTmpName);

        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO documents (name, type, size, content, note) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $fileName, $fileType, $fileSize, $fileContent, $note);

        if ($stmt->execute()) {
            echo "Le fichier a été téléchargé et enregistré avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement du fichier : " . $stmt->error;
        }

        // Fermer la déclaration et la connexion
        $stmt->close();
        $conn->close();
    } else {
        echo "Erreur de téléchargement de fichier.";
    }
}
?>
