<?php
require_once 'db_connection.php'; // Inclure votre fichier de connexion à la base de données

if (isset($_GET['id'])) {
    $id = $_GET['id']; // L'identifiant du fichier que vous souhaitez récupérer

    $stmt = $conn->prepare("SELECT name, type, content FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($fileName, $fileType, $fileContent);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        header("Content-Type: " . $fileType);
        header("Content-Disposition: attachment; filename=" . $fileName);
        echo $fileContent;
    } else {
        echo "Fichier non trouvé.";
    }

    $stmt->close();
} else {
    echo "ID de fichier non spécifié.";
}

