<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "SELECT * FROM projets WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    echo json_encode($row);  // Convertir les données en JSON

    $stmt->close();
} else {
    echo json_encode(array("error" => "Requête non autorisée."));
}

$conn->close();
?>
