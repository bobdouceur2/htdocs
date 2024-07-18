<?php
require_once 'db_connection.php';

echo "<link rel='stylesheet' type='text/css' href='style.css'>";
echo "<style>
    .date-depassee {
        color: red;
    }
</style>";

$levier = isset($_GET['levier']) ? $_GET['levier'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'dateAsc'; // Valeur par défaut
$showAll = isset($_GET['showAll']) ? $_GET['showAll'] : false;
$userId = $_SESSION['userId'];

// Déterminez l'ordre de tri basé sur la valeur du paramètre sort
switch ($sort) {
    case 'dateDesc':
        $orderBy = "DateDeDebut DESC";
        break;
    case 'avancementAsc':
        $orderBy = "Avancement ASC";
        break;
    case 'avancementDesc':
        $orderBy = "Avancement DESC";
        break;
    case 'dateAsc':
    default:
        $orderBy = "DateDeDebut ASC";
        break;
}

if ($showAll) {
    $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants FROM projets ORDER BY $orderBy";
} else {
    $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants FROM projets WHERE FIND_IN_SET(?, Participants) ORDER BY $orderBy";
}

if ($levier) {
    $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants FROM projets WHERE Levier = ? AND FIND_IN_SET(?, Participants) ORDER BY $orderBy";
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
        $dateDeFin = new DateTime($row["DateDeFin"]);
        $aujourdhui = new DateTime();
        $classeDateDepassee = ($dateDeFin < $aujourdhui) ? 'date-depassee' : '';

        echo "<tr>";
        echo "<td>" . $row["ID"] . "</td>";
        echo "<td>" . htmlspecialchars($row["Intitule"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Objectifs"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["DateDeDebut"]) . "</td>";
        echo "<td class='$classeDateDepassee'>" . htmlspecialchars($row["DateDeFin"]) . "</td>";
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
