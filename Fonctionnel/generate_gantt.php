<?php

require_once 'db_connection.php'; // Inclure la connexion à la base de données

// Fonction pour générer un diagramme de Gantt avec des barres de couleurs
function generateGanttChart($year, $month, $conn) {
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

    // Requête pour obtenir les projets pour le mois en cours
    $query = "SELECT ID, Intitule, DateDeDebut, DateDeFin 
              FROM projets 
              WHERE (MONTH(DateDeDebut) = ? AND YEAR(DateDeDebut) = ?) 
                 OR (MONTH(DateDeFin) = ? AND YEAR(DateDeFin) = ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $month, $year, $month, $year);
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
    }

    // Définir la plage de dates pour le diagramme de Gantt
    $firstDay = new DateTime("$year-$month-01");
    $lastDay = clone $firstDay;
    $lastDay->modify('last day of this month');
    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($firstDay, $interval, $lastDay->modify('+1 day'));

    // Afficher les contrôles de navigation
    echo "<div class='gantt-navigation'>";
    echo "<a href='?month=" . ($month - 1) . "&year=" . ($year - ($month == 1 ? 1 : 0)) . "' class='gantt-nav-icon prev'><i class='fas fa-chevron-left'></i></a>";
    echo "<span>Diagramme de Gantt : " . $mois_francais[(int)$month] . " " . $year . "</span>";
    echo "<a href='?month=" . ($month + 1) . "&year=" . ($year + ($month == 12 ? 1 : 0)) . "' class='gantt-nav-icon next'><i class='fas fa-chevron-right'></i></a>";
    echo "</div>";

    // Début du tableau du diagramme de Gantt
    echo "<table class='gantt-chart'>";
    echo "<thead><tr><th>Projet</th>";

    // Afficher les jours du mois
    foreach ($period as $dt) {
        echo "<th>" . $dt->format('d') . "</th>";
    }

    echo "</tr></thead><tbody>";

    // Afficher les projets et leurs barres de couleurs dans le Gantt
    foreach ($tasks as $task) {
        $colorClass = "color-" . $colorIndex;
        echo "<tr><td>{$task['Intitule']}</td>";
        $startDate = new DateTime($task['DateDeDebut']);
        $endDate = new DateTime($task['DateDeFin']);
        foreach ($period as $dt) {
            if ($dt >= $startDate && $dt <= $endDate) {
                echo "<td class='gantt-bar $colorClass'></td>";
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

