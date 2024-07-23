<?php
require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Utilisez le nom exact de la colonne dans votre requête SQL
    $sql = "SELECT * FROM projets WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Aucune ligne trouvée"]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "ID non spécifié"]);
}
$conn->close();
?>
