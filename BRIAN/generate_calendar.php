<?php


session_start(); // Démarrer la session

require_once 'db_connection.php'; // Inclure le fichier de connexion à la base de données

// Fonction de débogage pour afficher des messages dans la console du navigateur
function debug_to_console($data) {
    $output = $data;
    if (is_array($output)) {
        $output = implode(',', $output);
    }
    echo "<script>console.log('Debug: " . $output . "' );</script>";
}

// Fonction pour générer un diagramme de Gantt
function generateGanttChart($year, $month, $conn) {
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
    echo "<span>Diagramme de Gantt : " . $firstDay->format('F Y') . "</span>";
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

    // Afficher les projets et leur progression dans le Gantt
    foreach ($tasks as $task) {
        echo "<tr><td>{$task['Intitule']}</td>";
        $startDate = new DateTime($task['DateDeDebut']);
        $endDate = new DateTime($task['DateDeFin']);
        foreach ($period as $dt) {
            if ($dt >= $startDate && $dt <= $endDate) {
                echo "<td class='gantt-bar'></td>";
            } else {
                echo "<td></td>";
            }
        }
        echo "</tr>";
    }

    echo "</tbody></table>";

    // Ajouter du style pour le Gantt
    echo "<style>
        .gantt-chart {
            width: 100%;
            border-collapse: collapse;
        }
        .gantt-chart th, .gantt-chart td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }
        .gantt-bar {
            background-color: #28a745;
        }
        .gantt-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .gantt-nav-icon {
            text-decoration: none;
            color: #007bff;
        }
        .gantt-nav-icon:hover {
            text-decoration: underline;
        }
    </style>";
}

// Vérifier et récupérer les paramètres year et month de l'URL ou utiliser les valeurs par défaut
if (isset($_GET['year']) && isset($_GET['month'])) {
    $year = (int)$_GET['year'];
    $month = (int)$_GET['month'];
} else {
    $year = date('Y');
    $month = date('m');
}

// Appeler la fonction pour générer le diagramme de Gantt avec les paramètres récupérés
generateGanttChart($year, $month, $conn);

