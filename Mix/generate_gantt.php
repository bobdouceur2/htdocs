<?php

require_once 'db_connection.php'; // Inclure la connexion à la base de données

// Fonction pour générer un diagramme de Gantt avec des barres de couleurs
function generateGanttChart($year, $conn) {
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
    $query = "SELECT ID, Intitule, DateDeDebut, DateDeFin 
              FROM projets 
              WHERE YEAR(DateDeDebut) = ? OR YEAR(DateDeFin) = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $year, $year);
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

    // Début du tableau du diagramme de Gantt
    echo "<table class='gantt-chart'>";
    echo "<thead><tr><th>Projet</th>";

    // Afficher les mois de l'année
    foreach ($mois_francais as $mois) {
        echo "<th>$mois</th>";
    }

    echo "</tr></thead><tbody>";

    // Afficher les projets et leurs barres de couleurs dans le Gantt
    foreach ($tasks as $task) {
        $colorClass = "color-" . $colorIndex;
        echo "<tr><td>{$task['Intitule']}</td>";
        echo "<td colspan='12'>";
        echo "<div class='gantt-bar-container'>";

        $startDate = new DateTime($task['DateDeDebut']);
        $endDate = new DateTime($task['DateDeFin']);
        $startMonth = (int)$startDate->format('m');
        $endMonth = (int)$endDate->format('m');

        // Ajuster la largeur de la barre de Gantt en fonction des mois
        $totalMonths = 12;
        $startPercentage = (($startMonth - 1) / $totalMonths) * 100;
        $endPercentage = ($endMonth / $totalMonths) * 100;
        $widthPercentage = $endPercentage - $startPercentage;

        echo "<div class='gantt-bar $colorClass' style='width: $widthPercentage%; margin-left: $startPercentage%;'></div>";

        echo "</div></td></tr>";

        // Incrémenter l'index de couleur et le réinitialiser si nécessaire
        $colorIndex = ($colorIndex % $colorCount) + 1;
    }

    echo "</tbody></table>";
}
?>
