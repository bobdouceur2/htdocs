<?php
require_once 'db_connection.php';

echo "<link rel='stylesheet' type='text/css' href='style.css'>";

$levier = isset($_GET['levier']) ? $_GET['levier'] : null;
$sortDate = isset($_GET['sortDate']) ? $_GET['sortDate'] : 'asc'; // Valeur par défaut

if ($levier) {
    $stmt = $conn->prepare("SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier FROM projets WHERE Levier = ? ORDER BY DateDeDebut $sortDate");
    $stmt->bind_param("s", $levier);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier FROM projets ORDER BY DateDeDebut $sortDate");
}

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Intitulé</th><th>Objectifs</th><th>Date de début</th><th>Date de fin</th><th>Avancement (%)</th><th>Levier</th></tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ID"] . "</td>";
        echo "<td>" . htmlspecialchars($row["Intitule"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Objectifs"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["DateDeDebut"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["DateDeFin"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Avancement"]) . "%</td>";
        echo "<td>" . htmlspecialchars($row["Levier"]) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 résultats";
}

