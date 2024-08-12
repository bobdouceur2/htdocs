<!-- Code modifié dans fetch_data.php -->
<?php
require_once 'db_connection.php';

echo "<link rel='stylesheet' type='text/css' href='style.css'>";
// Vérifier si un levier a été spécifié dans la requête
$levier = isset($_GET['levier']) ? $_GET['levier'] : null;

// Préparer la requête SQL en fonction de la présence ou non d'un filtre levier
if ($levier) {
    // Utiliser des requêtes préparées pour éviter les injections SQL
    $stmt = $conn->prepare("SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier FROM projets WHERE Levier = ? ORDER BY ID DESC");
    $stmt->bind_param("s", $levier);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier FROM projets ORDER BY ID DESC");
}

// Vérifier si des lignes ont été retournées
if ($result->num_rows > 0) {
    // Début du tableau
    echo "<table border='1'>";
    // En-têtes du tableau
    echo "<tr><th>ID</th><th>Intitulé</th><th>Objectifs</th><th>Date de début</th><th>Date de fin</th><th>Avancement (%)</th><th>Levier</th></tr>";

    // Récupération et affichage de chaque ligne de la table
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ID"] . "</td>";
        echo "<td>" . htmlspecialchars($row["Intitule"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Objectifs"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["DateDeDebut"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["DateDeFin"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Avancement"]) . "%</td>"; // Ajout du logo "%" après la valeur d'avancement
        echo "<td>" . htmlspecialchars($row["Levier"]) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 résultats";
}

// Fermer la connexion à la base de données si vous avez terminé d'utiliser la connexion dans ce script;
?>
