<?php
require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    error_log(" ID REÇU : " . $id);

    $sql = "SELECT * FROM projets WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Aucun projet trouvé avec l'ID = $id"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "ID non spécifié"]);
}

