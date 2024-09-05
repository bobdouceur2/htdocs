<?php
// Activer le rapport d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connection.php';

// Vérifier la connexion à la base de données
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}




// Requête pour obtenir les noms des colonnes de la table "projets"
$query = "SHOW COLUMNS FROM projets";
$result = $conn->query($query);

// Tableau pour stocker les noms de colonnes
$columns = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
}

$result->free();






$project = null;
$jalons = []; // Initialiser le tableau des jalons

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Préparez et exécutez la requête SQL pour récupérer les données du projet
    $query = "SELECT * FROM projets WHERE ID = ?";
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
    <link rel="stylesheet" type="text/css" href="visualisation.css" defer>
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

    function togglePCDView(theme) {
        // Masquer toutes les cartes
        const allCards = document.querySelectorAll('.card');
        allCards.forEach(card => card.style.display = 'none');

        // Afficher les cartes en fonction du thème sélectionné
        if (theme === 'Progrès') {
            const progressColumns = ['objectifs', 'datededebut', 'datedefin', 'avancement', 'etapes', 'planification', 'objectifs-operationnels', 'perimetre', 'equipe'];
            progressColumns.forEach(column => {
                const card = document.querySelector(`.card.${column}`);
                if (card) card.style.display = 'block';
            });
        } else if (theme === 'Compétitivité') {
            const competitivenessColumns = ['levier', 'type-de-gain', 'source-de-financement', 'kpi', 'priorite', 'axe-pf-se'];
            competitivenessColumns.forEach(column => {
                const card = document.querySelector(`.card.${column}`);
                if (card) card.style.display = 'block';
            });
        } else if (theme === 'Digitalisation') {
            const digitalizationColumns = ['digitalisation', 'di', 'dt', 'modifications'];
            digitalizationColumns.forEach(column => {
                const card = document.querySelector(`.card.${column}`);
                if (card) card.style.display = 'block';
            });
        }
    }

    function applySettings() {
        // Récupère le formulaire des paramètres
        var form = document.getElementById('settings-form');

        // Sélectionne toutes les cases à cocher du formulaire
        var checkboxes = form.querySelectorAll('input[name="fields[]"]');

        // Parcourt chaque case à cocher
        checkboxes.forEach(function(checkbox) {
            // Convertit la valeur de la case à cocher en minuscule pour correspondre aux classes CSS
            var field = checkbox.value.toLowerCase();

            // Remplace les espaces et les underscores pour correspondre aux noms de classe CSS
            field = field.replace(/ /g, '-').replace('_', '-');

            // Sélectionne la carte (div) qui correspond à la colonne
            var card = document.querySelector('.card.' + field);

            // Vérifie si la carte existe
            if (card) { 
                // Affiche ou masque la carte en fonction de l'état de la case à cocher
                if (checkbox.checked) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }

            // Gère spécifiquement les cas comme "DatesJalon" ou d'autres champs similaires
            if (field === 'datesjalon') {
                // Sélectionne la carte associée aux dates jalons
                var datesJalonCard = document.querySelector('.card.dates-jalon');

                // Affiche ou masque la carte des dates jalons en fonction de l'état de la case à cocher
                if (datesJalonCard) {
                    datesJalonCard.style.display = checkbox.checked ? 'block' : 'none';
                }
            }
        });

        // Ferme le popup après avoir appliqué les paramètres
        closePopup();
    }


    function closePopup() {
        document.getElementById('settings-popup').style.display = 'none';
    }

    document.getElementById('settings-icon').addEventListener('click', function() {
        document.getElementById('settings-popup').style.display = 'block';
    });


    function togglePopup() {
        var popup = document.getElementById('settings-popup');
        if (popup.style.display === 'block') {
            popup.style.display = 'none';
        } else {
            popup.style.display = 'block';
        }
    }

    document.getElementById('settings-icon').addEventListener('click', togglePopup);












    </script>
</head>
<body>
    <header>
        <!-- Bouton pour l'icône Triangle PCD -->
        <button class="pcd-button" onclick="document.getElementById('pcd-popup').style.display = 'block'">
            <i class="fas fa-caret-up"></i> <!-- Utiliser caret-up comme triangle -->
            Triangle PCD
        </button>
        
        <div class="header-spacer"></div>
        <h1>Visualisation pour l'ID: <?php echo htmlspecialchars($id ?? ''); ?></h1>
        <i class="fas fa-cog" id="settings-icon"></i>
    </header>

    <!-- Popup pour le choix des thèmes PCD -->
    <div id="pcd-popup" class="popup-form">
        <div class="popup-content">
            <span class="close-icon" onclick="document.getElementById('pcd-popup').style.display = 'none'">
                <i class="fa-regular fa-rectangle-xmark"></i>
            </span>
            <h2>Choisissez un thème à afficher</h2>
            <button onclick="togglePCDView('Progrès')">Progrès</button>
            <br><br>
            <button onclick="togglePCDView('Compétitivité')">Compétitivité</button>
            <br><br>
            <button onclick="togglePCDView('Digitalisation')">Digitalisation</button>
        </div>
    </div>

    <!-- Popup pour la sélection des colonnes à afficher -->
    <div class="popup-form" id="settings-popup">
        <div class="popup-content">
            <span class="close-icon" onclick="closePopup()">
                <i class="fa-regular fa-rectangle-xmark"></i>
            </span>

            <h2>Choisissez les données à afficher</h2>

            <form id="settings-form">
            <div class="settings-controls">
                <button type="button" class="settings-button" onclick="toggleAllCheckboxes(true)">Tout sélectionner</button>
                <button type="button" class="settings-button" onclick="toggleAllCheckboxes(false)">Tout désélectionner</button>
                <button type="button" class="settings-button" onclick="resetToDefaults()">Réinitialiser</button>
            </div>
            
            <form id="settings-form">
                <?php
                // Générer des cases à cocher pour chaque colonne
                foreach ($columns as $column) {
                    $checked = 'checked'; // Toutes les cases sont cochées par défaut
                    echo "<label><input type='checkbox' name='fields[]' value='$column' $checked> $column</label><br>";
                }
                ?>
                <button type="button" onclick="applySettings()">Appliquer</button>
            </form>
        </div>
    </div>


    <div class="dashboard">
        <?php
        // Générer les cartes pour chaque colonne
        foreach ($columns as $column) {
            // Contenu par défaut si la colonne est vide
            $content = isset($project[$column]) ? $project[$column] : 'Aucune donnée disponible.';

            // Remplacer les espaces et caractères spéciaux pour les utiliser comme classe CSS
            $columnClass = strtolower(str_replace([' ', '_'], '-', $column));

            echo "<div class='card $columnClass'>";
            echo "<h2>" . htmlspecialchars($column) . "</h2>";
            echo "<p>" . htmlspecialchars($content) . "</p>";
            echo "</div>";
        }
        ?>
    </div>


    <script>

        function toggleAllCheckboxes(selectAll) {
                    const checkboxes = document.querySelectorAll('#settings-form input[type="checkbox"]');
                    checkboxes.forEach(checkbox => checkbox.checked = selectAll);
                }

                function resetToDefaults() {
                    const checkboxes = document.querySelectorAll('#settings-form input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = ['Intitule', 'Objectifs', 'Levier', 'Avancement', 'Participants', 'Localisation', 'DatesJalon', 'DescriptionProbleme', 'ObjectifsOperationnels', 'Perimetre', 'Planning', 'Equipe'].includes(checkbox.value);
                    });
                }

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

            // Vérifiez spécifiquement pour "Dates Jalon" et autres champs similaires
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
