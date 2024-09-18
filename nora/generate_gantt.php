<?php

require_once 'db_connection.php'; // Inclure la connexion à la base de données

// Fonction pour générer un diagramme de Gantt avec des barres de couleurs et des liens cliquables
function generateGanttChart($startDate, $monthSpan, $conn, $userId, $showAll) {
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

    // Calculer la date de fin
    $endDate = clone $startDate;
    $endDate->modify("+$monthSpan months");

    // Préparer les dates pour la requête SQL
    $startDateStr = $startDate->format('Y-m-d');
    $endDateStr = $endDate->format('Y-m-d');

    if ($showAll) {
        // Si l'utilisateur choisit d'afficher tous les projets
        $query = "SELECT ID, Intitule, DateDeDebut, DateDeFin 
                  FROM projets 
                  WHERE (DateDeFin >= ?) AND (DateDeDebut <= ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDateStr, $endDateStr);
    } else {
        // Si l'utilisateur choisit d'afficher uniquement ses projets (participant dans l'équipe)
        $query = "SELECT ID, Intitule, DateDeDebut, DateDeFin 
                  FROM projets 
                  WHERE (DateDeFin >= ?) AND (DateDeDebut <= ?)
                     AND (Equipe = ? OR Equipe LIKE ?)";
        $stmt = $conn->prepare($query);
        $searchUserId = "%" . $userId . "%";
        $stmt->bind_param("ssss", $startDateStr, $endDateStr, $userId, $searchUserId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Générer les données du Gantt
    $tasks = [];
    $colorIndex = 1; // Index pour les couleurs
    $colorCount = 20; // Nombre de couleurs disponibles
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    } else {
        echo "Aucun projet trouvé pour la période sélectionnée.<br>";
    }

    // Afficher les contrôles de navigation
    $prevStartDate = clone $startDate;
    $prevStartDate->modify('-6 months');
    $nextStartDate = clone $startDate;
    $nextStartDate->modify('+6 months');

    // Limiter le $monthSpan entre 6 et 60 mois
    if ($monthSpan < 6) {
        $monthSpan = 6;
    } elseif ($monthSpan > 60) {
        $monthSpan = 60;
    }

    echo "<div class='gantt-navigation'>";
    echo "<a href='?startDate=" . $prevStartDate->format('Y-m-d') . "&monthSpan=$monthSpan&showAll=" . ($showAll ? 'true' : 'false') . "' class='gantt-nav-icon prev'><i class='fas fa-chevron-left'></i></a>";
    echo "<a href='?startDate=" . $startDate->format('Y-m-d') . "&monthSpan=" . ($monthSpan - 6) . "&showAll=" . ($showAll ? 'true' : 'false') . "' class='gantt-nav-icon minus'><i class='fas fa-minus'></i></a>";
    echo "<span>Période du " . $startDate->format('d/m/Y') . " au " . $endDate->format('d/m/Y') . "</span>";
    echo "<a href='?startDate=" . $startDate->format('Y-m-d') . "&monthSpan=" . ($monthSpan + 6) . "&showAll=" . ($showAll ? 'true' : 'false') . "' class='gantt-nav-icon plus'><i class='fas fa-plus'></i></a>";
    echo "<a href='?startDate=" . $nextStartDate->format('Y-m-d') . "&monthSpan=$monthSpan&showAll=" . ($showAll ? 'true' : 'false') . "' class='gantt-nav-icon next'><i class='fas fa-chevron-right'></i></a>";
    echo "</div>";

    // Début du tableau du diagramme de Gantt
    echo "<table class='gantt-chart'>";
    echo "<thead><tr><th>Projet</th>";

    // Calcul du pas pour l'affichage des mois
    $monthStep = ($monthSpan > 12) ? ceil($monthSpan / 12) : 1;

    // Afficher les mois de la période
    for ($i = 0; $i < $monthSpan; $i++) {
        $currentMonth = clone $startDate;
        $currentMonth->modify("+$i months");
        $monthNum = (int)$currentMonth->format('n');
        $yearNum = (int)$currentMonth->format('Y');

        if ($i % $monthStep == 0) {
            echo "<th>" . $mois_francais[$monthNum] . " " . $yearNum . "</th>";
        } else {
            echo "<th></th>";
        }
    }

    echo "</tr></thead><tbody>";

    // Fixer la largeur du conteneur et ajuster la largeur des colonnes
    $tableWidth = 100; // Largeur en pourcentage
    $columnWidth = $tableWidth / $monthSpan;

    // Afficher les projets et leurs barres de couleurs dans le Gantt
    foreach ($tasks as $task) {
        $colorClass = "color-" . $colorIndex;
        $projectId = $task['ID']; // Récupérer l'ID du projet

        echo "<tr><td>{$task['Intitule']}</td>";
        echo "<td colspan='$monthSpan'>";
        echo "<div class='gantt-bar-container'>";

        $projectStart = new DateTime($task['DateDeDebut']);
        $projectEnd = new DateTime($task['DateDeFin']);

        // Limiter les dates du projet à la plage affichée
        $barStartDate = ($projectStart > $startDate) ? $projectStart : $startDate;
        $barEndDate = ($projectEnd < $endDate) ? $projectEnd : $endDate;

        // Calcul du total des jours dans la plage affichée
        $totalDays = $endDate->getTimestamp() - $startDate->getTimestamp();
        $totalDays = $totalDays / (60 * 60 * 24);

        // Calcul du début et de la fin de la barre en pourcentage
        $barStartDays = $barStartDate->getTimestamp() - $startDate->getTimestamp();
        $barStartDays = $barStartDays / (60 * 60 * 24);
        $barEndDays = $barEndDate->getTimestamp() - $startDate->getTimestamp();
        $barEndDays = $barEndDays / (60 * 60 * 24);

        $startPercentage = ($barStartDays / $totalDays) * 100;
        $endPercentage = ($barEndDays / $totalDays) * 100;
        $widthPercentage = $endPercentage - $startPercentage;

        // Créer un lien cliquable pour chaque barre qui ouvre la page de visualisation dans une nouvelle fenêtre
        echo "<a href='javascript:void(0);' onclick='openProjectVisualization($projectId)'>";
        echo "<div class='gantt-bar $colorClass' data-start='{$barStartDate->format('d/m/Y')}' data-end='{$barEndDate->format('d/m/Y')}' style='width: $widthPercentage%; margin-left: $startPercentage%;'></div>";
        echo "</a>";

        echo "</div></td></tr>";

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
    $startDateStr = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');
    $monthSpan = isset($_GET['monthSpan']) ? (int)$_GET['monthSpan'] : 12;
    $showAll = isset($_GET['showAll']) && $_GET['showAll'] === 'true';

    // Créer l'objet DateTime pour la date de début
    $startDate = new DateTime($startDateStr);

    // Génération du diagramme de Gantt
    generateGanttChart($startDate, $monthSpan, $conn, $userId, $showAll);
} else {
    echo "Utilisateur non connecté.";
}
?>

<!-- Ajout du script pour ouvrir la fenêtre de visualisation -->
<script>
function openProjectVisualization(projectId) {
    // Ouvrir la page de visualisation du projet dans une nouvelle fenêtre
    window.open('visualization.php?id=' + projectId, '_blank');
}
</script>
