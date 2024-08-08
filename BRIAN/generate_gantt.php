<?php

require_once 'db_connection.php'; // Inclure la connexion à la base de données

// Fonction pour générer un diagramme de Gantt avec des barres de couleurs
function generateGanttChart($year, $conn, $userId, $showAll) {
    // Tableau des mois en français
    $mois_francais = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    ];

    // Requête pour obtenir les projets pour l'année en cours
    if ($showAll) {
        $query = "SELECT ID, Intitule, DateDeDebut, DateDeFin 
                  FROM projets 
                  WHERE YEAR(DateDeDebut) = ? 
                     OR YEAR(DateDeFin) = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $year, $year);
    } else {
        $query = "SELECT ID, Intitule, DateDeDebut, DateDeFin 
                  FROM projets 
                  WHERE (YEAR(DateDeDebut) = ? 
                     OR YEAR(DateDeFin) = ?)
                     AND (Participants = ? OR Participants LIKE ?)";
        $stmt = $conn->prepare($query);
        $searchUserId = "%" . $userId . "%";
        $stmt->bind_param("iiss", $year, $year, $userId, $searchUserId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Générer les données du Gantt
    $tasks = [];
    $colorIndex = 1; // Index pour les couleurs
    $colorCount = 10; // Nombre de couleurs disponibles
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    } else {
        echo "Aucun projet trouvé pour l'année $year.<br>";
    }

    // Définir la plage de dates pour le diagramme de Gantt
    $firstMonth = 1;
    $lastMonth = 12;

    // Afficher les contrôles de navigation
    echo "<div class='gantt-navigation'>";
    echo "<a href='?year=" . ($year - 1) . "&showAll=" . ($showAll ? 'true' : 'false') . "' class='gantt-nav-icon prev'><i class='fas fa-chevron-left'></i></a>";
    echo "<span>Année " . $year . "</span>";
    echo "<a href='?year=" . ($year + 1) . "&showAll=" . ($showAll ? 'true' : 'false') . "' class='gantt-nav-icon next'><i class='fas fa-chevron-right'></i></a>";
    echo "</div>";

    // Début du tableau du diagramme de Gantt
    echo "<table class='gantt-chart'>";
    echo "<thead><tr><th>Projet</th>";

    // Afficher les mois de l'année
    for ($month = $firstMonth; $month <= $lastMonth; $month++) {
        echo "<th>" . $mois_francais[$month] . "</th>";
    }

    echo "</tr></thead><tbody>";

    // Afficher les projets et leurs barres de couleurs dans le Gantt
    foreach ($tasks as $task) {
        $colorClass = "color-" . $colorIndex;
        echo "<tr><td>{$task['Intitule']}</td>";
        $startDate = new DateTime($task['DateDeDebut']);
        $endDate = new DateTime($task['DateDeFin']);
        
        $formattedStartDate = $startDate->format('d/m/Y');
        $formattedEndDate = $endDate->format('d/m/Y');
        
        $startMonth = (int) $startDate->format('n');
        $endMonth = (int) $endDate->format('n');
        $startYear = (int) $startDate->format('Y');
        $endYear = (int) $endDate->format('Y');
    
        for ($month = $firstMonth; $month <= $lastMonth; $month++) {
            if (($startYear < $year || ($startYear == $year && $month >= $startMonth)) &&
                ($endYear > $year || ($endYear == $year && $month <= $endMonth))) {
                echo "<td class='gantt-bar $colorClass' data-start='{$formattedStartDate}' data-end='{$formattedEndDate}'></td>";
            } else {
                echo "<td></td>";
            }
        }
        echo "</tr>";
        
        // Incrémenter l'index de couleur et le réinitialiser si nécessaire
        $colorIndex = ($colorIndex % $colorCount) + 1;
    }

    echo "</tbody></table>";
}

// Récupération de l'ID de l'utilisateur connecté depuis la session
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

// Assurer que l'ID utilisateur est valide avant de générer le Gantt
if ($userId) {
    // Récupération des paramètres de la requête GET
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    $showAll = isset($_GET['showAll']) && $_GET['showAll'] === 'true';

    // Génération du diagramme de Gantt
    generateGanttChart($year, $conn, $userId, $showAll);
} else {
    echo "Utilisateur non connecté.";
}

