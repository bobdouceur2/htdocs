<?php
require_once 'db_connection.php';

echo "<link rel='stylesheet' type='text/css' href='style.css'>";

$levier = isset($_GET['levier']) ? $_GET['levier'] : null;
$sortDate = isset($_GET['sortDate']) ? $_GET['sortDate'] : 'asc'; // Valeur par défaut
$showAll = isset($_GET['showAll']) ? $_GET['showAll'] : false;
$userId = $_SESSION['userId'];

if ($showAll) {
    $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants FROM projets ORDER BY DateDeDebut $sortDate";
} else {
    $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants FROM projets WHERE FIND_IN_SET(?, Participants) ORDER BY DateDeDebut $sortDate";
}

if ($levier) {
    $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants FROM projets WHERE Levier = ? AND FIND_IN_SET(?, Participants) ORDER BY DateDeDebut $sortDate";
}

if ($levier) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $levier, $userId);
} else {
    if ($showAll) {
        $stmt = $conn->prepare($query);
    } else {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $userId);
    }
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Intitulé</th><th>Objectifs</th><th>Date de début</th><th>Date de fin</th><th>Avancement (%)</th><th>Levier</th><th>Participants</th></tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ID"] . "</td>";
        echo "<td>" . htmlspecialchars($row["Intitule"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Objectifs"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["DateDeDebut"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["DateDeFin"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Avancement"]) . "%</td>";
        echo "<td>" . htmlspecialchars($row["Levier"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Participants"]) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 résultats";
}
?>
