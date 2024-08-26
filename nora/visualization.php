<?php
// Activer le rapport d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connection.php';

// Vérifier la connexion à la base de données
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

$project = null;
$jalons = []; // Initialiser le tableau des jalons

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Préparez et exécutez la requête SQL pour récupérer les données du projet
    $query = "SELECT ID, Intitule, DateDeDebut, DateDeFin, Avancement, Levier, Participants, Localisation, dates_jalon FROM projets WHERE ID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifiez si des données ont été trouvées
    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
        // Formatage des dates
        $startDate = new DateTime($project['DateDeDebut']);
        $endDate = new DateTime($project['DateDeFin']);

        $formattedStartDate = $startDate->format('d/m/Y');
        $formattedEndDate = $endDate->format('d/m/Y');

        // Décodez les dates jalons si elles existent
        if (!empty($project['dates_jalon'])) {
            $jalons = json_decode($project['dates_jalon'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Erreur de décodage JSON: " . json_last_error_msg();
                $jalons = []; // Réinitialiser en cas d'erreur JSON
            }
        }
    } else {
        echo "Aucun projet trouvé avec cet ID.<br>";
    }
} else {
    echo "Aucun ID spécifié.<br>";
}

function generateGanttChart($project) {
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

    // Définir la plage de dates pour le diagramme de Gantt
    $firstMonth = 1;
    $lastMonth = 12;
    $year = date('Y');

    echo "<table class='gantt-chart'>";
    echo "<thead><tr><th>Projet</th>";

    // Afficher les mois de l'année
    for ($month = $firstMonth; $month <= $lastMonth; $month++) {
        echo "<th>" . $mois_francais[$month] . "</th>";
    }

    echo "</tr></thead><tbody>";

    // Afficher le projet et sa barre de couleurs dans le Gantt
    $colorClass = "color-1";
    echo "<tr><td>{$project['Intitule']}</td>";
    echo "<td colspan='12'>";
    echo "<div class='gantt-bar-container'>";

    $startDate = new DateTime($project['DateDeDebut']);
    $endDate = new DateTime($project['DateDeFin']);

    $startYear = (int)$startDate->format('Y');
    $endYear = (int)$endDate->format('Y');
    $startMonth = ($startYear < $year) ? 1 : (int)$startDate->format('m');
    $endMonth = ($endYear > $year) ? 12 : (int)$endDate->format('m');

    $totalMonths = 12;
    $startPercentage = (($startMonth - 1) / $totalMonths) * 100;
    $endPercentage = ($endMonth / $totalMonths) * 100;
    $widthPercentage = $endPercentage - $startPercentage;

    echo "<div class='gantt-bar $colorClass' data-start='{$startDate->format('d/m/Y')}' data-end='{$endDate->format('d/m/Y')}' style='width: $widthPercentage%; margin-left: $startPercentage%;'></div>";

    echo "</div></td></tr>";
    echo "</tbody></table>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisation du projet</title>
    <link rel="stylesheet" type="text/css" href="visualisation.css">
    <!-- FontAwesome pour l'icône de réglage -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Script Google Maps et Google Places API -->
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1JmYjZBnnWhmmfCmoNylVAN3DQ2a0voI&libraries=places&callback=initMap"></script>
    <script>
    function initMap() {
        const address = "<?php echo htmlspecialchars($project['Localisation'] ?? ''); ?>";
        
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: { lat: -34.397, lng: 150.644 }  // Coordonnées par défaut
        });
        
        const geocoder = new google.maps.Geocoder();
        const service = new google.maps.places.PlacesService(map);

        // Géocoder pour rechercher l'adresse ou l'emplacement de l'entreprise
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status === 'OK') {
                map.setCenter(results[0].geometry.location);
                const marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
            } else if (status !== 'ZERO_RESULTS') {
                console.error('Geocode was not successful for the following reason: ' + status);
            }
        });

        // Rechercher des établissements spécifiques comme des entreprises
        service.findPlaceFromQuery({
            query: address,
            fields: ['name', 'geometry']
        }, function(results, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK && results) {
                for (let i = 0; i < results.length; i++) {
                    map.setCenter(results[i].geometry.location);
                    const marker = new google.maps.Marker({
                        map: map,
                        position: results[i].geometry.location
                    });
                }
            } else if (status !== google.maps.places.PlacesServiceStatus.ZERO_RESULTS) {
                console.error('Place search was not successful for the following reason: ' + status);
            }
        });
    }
    </script>
</head>
<body>
    <header>
        <div class="header-spacer"></div>
        <h1>Visualisation pour l'ID: <?php echo htmlspecialchars($id ?? ''); ?></h1>
        <i class="fas fa-cog" id="settings-icon"></i>
    </header>

    <div class="popup-form" id="settings-popup">
        <div class="popup-content">
            <h2>Choisissez les données à afficher</h2>
            <form id="settings-form">
                <label><input type="checkbox" name="fields[]" value="Intitule" checked> Intitulé</label><br>
                <label><input type="checkbox" name="fields[]" value="Objectifs" checked> Objectifs</label><br>
                <label><input type="checkbox" name="fields[]" value="Levier" checked> Levier</label><br>
                <label><input type="checkbox" name="fields[]" value="Avancement" checked> Avancement</label><br>
                <label><input type="checkbox" name="fields[]" value="Participants" checked> Participants</label><br>
                <label><input type="checkbox" name="fields[]" value="Localisation" checked> Localisation</label><br>
                <label><input type="checkbox" name="fields[]" value="DatesJalon" checked> Dates Jalon</label><br>
                <button type="button" onclick="applySettings()">Appliquer</button>
                <button type="button" onclick="closePopup()">Annuler</button>
            </form>
        </div>
    </div>

    <div class="dashboard">
    <?php if ($project): ?>
        <div class="card large intitule">
            <h2>Intitulé</h2>
            <p><?php echo htmlspecialchars($project['Intitule'] ?? ''); ?></p>
        </div>
        <div class="card small objectifs">
            <h2>Objectifs</h2>
            <p><?php echo htmlspecialchars($project['Objectifs'] ?? ''); ?></p>
        </div>

        <div class="card small levier">
            <h2>Levier</h2>
            <p><?php echo htmlspecialchars($project['Levier'] ?? ''); ?></p>
        </div>

        <div class="card large avancement">
            <h2>Avancement (%)</h2>
            <p><?php echo htmlspecialchars($project['Avancement'] ?? ''); ?>%</p>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo htmlspecialchars($project['Avancement'] ?? ''); ?>%;"></div>
            </div>
        </div>

        <div class="card large gantt">
            <h2>Dates de Début - Fin : <?php echo $formattedStartDate; ?> - <?php echo $formattedEndDate; ?></h2>
            <?php generateGanttChart($project); ?>
        </div>
        
        <div class="card small localisation">
            <h2>Localisation</h2>
            <p><?php echo htmlspecialchars($project['Localisation'] ?? ''); ?></p>
            <div id="map"></div>
        </div>

        <!-- Nouvelle carte pour les Dates Jalons -->
        <div class="card small dates-jalon">
            <h2>Dates Jalons</h2>
            <?php if (!empty($jalons)): ?>
                <ul>
                    <?php foreach ($jalons as $jalon): ?>
                        <li><?php echo htmlspecialchars($jalon['date'] . ' - ' . ($jalon['text'] ?? 'Pas de description disponible')); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune date jalon disponible.</p>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <p>Aucune donnée de projet à afficher.</p>
    <?php endif; ?>
</div>


<script>
    function applySettings() {
        var form = document.getElementById('settings-form');
        var checkboxes = form.querySelectorAll('input[name="fields[]"]');
        
        checkboxes.forEach(function(checkbox) {
            var field = checkbox.value.toLowerCase();

            // Remplacer les espaces et les tirets bas par des tirets pour correspondre aux classes CSS
            field = field.replace(/ /g, '-').replace('_', '-');

            var card = document.querySelector('.card.' + field);
            if (card) { // Vérifiez si l'élément existe
                if (checkbox.checked) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }

            // Vérifiez spécifiquement pour "Dates Jalon"
            if (field === 'datesjalon') {
                var datesJalonCard = document.querySelector('.card.dates-jalon');
                if (datesJalonCard) {
                    if (checkbox.checked) {
                        datesJalonCard.style.display = 'block';
                    } else {
                        datesJalonCard.style.display = 'none';
                    }
                }
            }
        });

        closePopup();
    }

    function closePopup() {
        document.getElementById('settings-popup').style.display = 'none';
    }

    document.getElementById('settings-icon').addEventListener('click', function() {
        document.getElementById('settings-popup').style.display = 'block';
    });
</script>


</body>
</html>
