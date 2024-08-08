<?php
session_start();
if (!isset($_SESSION['userId'])) {
    // Rediriger vers index.php si l'identifiant n'est pas défini
    header('Location: index.php');
    exit();
}
$userId = $_SESSION['userId'];

require_once 'db_connection.php';

// Requête pour obtenir le dernier ID utilisé
$query = "SELECT MAX(ID) as max_id FROM projets";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$nextId = $row['max_id'] + 1; // Calculer le prochain ID disponible
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de Projets</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="gantt.css"> <!-- Changer calendar.css en gantt.css pour le diagramme de Gantt -->
    <script src="functions.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Ajout des balises de lien pour charger la police depuis Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>

<body>

    <header class="site-header">
        <img src="logosafran.png" alt="Logo Safran" id="logoSafran" />
        <div class="admin-header">
            <div class="userid">Utilisateur: <?php echo htmlspecialchars($userId); ?></div>
        </div>
    </header>

    <div class="main-container">
        <!-- Nouvelle colonne à gauche -->
        <div class="sidebar">
            <!-- Regroupement des éléments de recherche, filtrage et tri -->
            <div class="form-group">
                <div class="search-container">
                    <i class="fas fa-search search-icon" onclick="toggleSearch()"></i>
                    <input type="text" id="searchInput" class="form-control mb-2" placeholder="Rechercher...">
                </div>
            </div>

            

            <i class="fas fa-plus-circle add-icon" onclick="openPopupForm()"></i>

            <i class="fas fa-chart-line view-icon" onclick="openVisualizationPopUp()" title="Visualisation"></i>

            <div id="visualizationPopupForm" class="popup-form">
                <div class="popup-content">
                    <span class="close" onclick="closeVisualizationPopupForm()">&times;</span>
                    <h2>Entrer l'ID pour la visualisation</h2> 
                    <form id="visualizationForm" class="form-container">
                        <label for="visualizationId"><b>ID</b></label>
                        <input type="number" id="visualizationId" placeholder="Entrer l'ID" name="id" required>
                        <button type="button" class="btn btn-primary" onclick="submitVisualizationForm()">Soumettre</button>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const input = document.getElementById("visualizationId");
                    input.addEventListener("keydown", function(event) {
                        if (event.key === "Enter") {
                            event.preventDefault();  // Empêche le formulaire de soumettre de manière traditionnelle
                            submitVisualizationForm();  // Appelle la fonction de soumission
                        }
                    });
                });
            </script>

            <div class="form-group">
                <!-- Ajout du dropdown Filtrer par levier -->
                <form id="filterForm" class="form-inline">
                    <select id="levierSelect" name="levier" class="form-control mb-2" style="width: 100%;" onchange="filterByLevier()">
                        <option value="">Filtrer par Levier</option>
                        <?php include 'levier_options.php'; ?>
                    </select>
                </form>
            </div>

            <div class="form-group">
                <!-- Ajout du dropdown Trier par date ou avancement -->
                <form id="sortForm" class="form-inline">
                    <select id="sortSelect" name="sort" class="form-control mb-2" style="width: 100%;" onchange="sortProjects()">
                        <option value="">Trier par</option>
                        <option value="dateAsc">Date de début croissante</option>
                        <option value="dateDesc">Date de début décroissante</option>
                        <option value="avancementAsc">Avancement croissant</option>
                        <option value="avancementDesc">Avancement décroissant</option>
                    </select>
                </form>
            </div>

            <button class="modern-button" onclick="showAllProjects()">
                <i class="fas fa-tasks"></i> Afficher tous les projets
            </button>
            <button class="modern-button secondary" onclick="showMyProjects()">
                <i class="fas fa-user"></i> Afficher uniquement les projets me concernant
            </button>

            <button class="modern-button logout-button" onclick="logoutUser()">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </button>

            <button class="modern-button settings-button" onclick="openSettingsPopup()">
                 <i class="fas fa-cog"></i> Réglages
            </button>

                                          

        </div>
      

        <!-- Conteneur de contenu principal -->
        <div class="content" id="mainContent">

    <!-- Affichage du diagramme de Gantt -->
    <div id="gantt-container">
        <?php
        // Vérifier et récupérer les paramètres year et month de l'URL ou utiliser les valeurs par défaut
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

        // Inclure le fichier de génération du diagramme de Gantt
        require_once 'generate_gantt.php';

        
        ?>
    </div>

<div id="tableContainer">
    <?php require_once 'fetch_data.php'; ?>
</div>

</div>


        <!-- Colonne à droite -->
        <div class="right-sidebar" id="rightSidebar">
            <button class="btn btn-primary btn-custom" onclick="openEditPopupForm()">Modifier la ligne</button>
            <button class="btn btn-danger btn-custom" onclick="deleteSelectedRow()">Supprimer la ligne</button>
        </div>
    </div>

    <div id="popupForm" class="popup-form">
        <div class="popup-content">
            <span class="close" onclick="closePopupForm()">&times;</span>
            <h2>Ajouter une nouvelle ligne</h2>
            <form id="addRowForm" class="form-container">
                <label for="id"><b>ID</b></label>
                <input type="number" id="id" placeholder="Entrer l'ID" name="id" required value="<?php echo $nextId; ?>">

                <label for="intitule"><b>Intitulé</b></label>
                <input type="text" id="intitule" placeholder="Entrer l'intitulé" name="intitule" required>

                <label for="objectifs"><b>Objectifs</b></label>
                <input type="text" id="objectifs" placeholder="Entrer les objectifs" name="objectifs" required>

                <label for="datededebut"><b>Date de début</b></label>
                <input type="date" id="datededebut" name="datededebut" required>

                <label for="datedefin"><b>Date de fin</b></label>
                <input type="date" id="datedefin" name="datedefin" required>

                <label for="avancement"><b>Avancement</b></label>
                <input type="range" min="0" max="100" value="0" class="slider" id="avancement" name="avancement">
                <span id="avancementValue">0%</span>

                <br><br>

                <label for="participants"><b>Participants</b></label>
                <input type="text" id="participants" placeholder="Entrer les participants" name="participants" required>

                <label for="levier"><b>Levier</b></label>
                <select id="levier" name="levier" required>
                    <option value="">Sélectionner un levier</option>
                    <?php include 'levier_options.php'; ?>
                </select>

                <label for="localisation"><b>Localisation</b></label>
                <input type="text" id="localisation" placeholder="Entrer la localisation" name="localisation" required>

                <i class="fas fa-plus-circle add-icon" onclick="addRow()"></i>
            </form>
            <div id="message" style="display: none;"></div>
        </div>
    </div>

    <div id="settingsPopupForm" class="popup-form">
        <div class="popup-content">
            <span class="close" onclick="closeSettingsPopup()">&times;</span>
            <h2>Réglages</h2>
            <button class="btn btn-primary" onclick="goToAdminMode()">Mode Administrateur</button>
        </div>
    </div>


    <script>
        document.getElementById('avancement').addEventListener('input', function() {
            document.getElementById('avancementValue').textContent = this.value + '%';
        });
    </script>                       

    <div id="editPopupForm" class="popup-form">
        <div class="popup-content">
            <span class="close" onclick="closeEditPopupForm()">&times;</span>
            <h2>Modifier une ligne</h2>
            <form id="editRowForm" class="form-container">
                <input type="hidden" id="originalId" name="original_id"> <!-- Champ caché pour l'ID original -->

                <label for="editId"><b>ID</b></label>
                <input type="text" id="editId" name="id" readonly>

                <label for="editIntitule"><b>Intitulé</b></label>
                <input type="text" id="editIntitule" name="intitule" required>

                <label for="editObjectifs"><b>Objectifs</b></label>
                <input type="text" id="editObjectifs" name="objectifs" required>

                <label for="editDatededebut"><b>Date de début</b></label>
                <input type="date" id="editDatededebut" name="datededebut" required>

                <label for="editDatedefin"><b>Date de fin</b></label>
                <input type="date" id="editDatedefin" name="datedefin" required>

                <label for="editAvancement"><b>Avancement</b></label>
                <input type="range" min="0" max="100" id="editAvancement" name="avancement">
                <span id="editAvancementValue">0%</span>

                <br><br>

                <label for="editParticipants"><b>Participants</b></label>
                <input type="text" id="editParticipants" name="participants" required>

                <label for="editLevier"><b>Levier</b></label>
                <select id="editLevier" name="levier" required>
                    <option value="">Sélectionner un levier</option>
                    <?php include 'levier_options.php'; ?>
                </select>

                <button type="button" onclick="saveEditedRow()">Sauvegarder</button>
            </form>
        </div>
    </div>

    <script>
        // Ajout de l'événement pour la touche Entrée sur le champ de recherche
        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Empêche le comportement par défaut du formulaire
                searchTable();
            }
        });

        // Sélectionner les éléments du slider et du span associés à l'avancement
        const editSlider = document.getElementById('editAvancement');
        const editAvancementValue = document.getElementById('editAvancementValue');

        // Écouter les changements de valeur du slider
        editSlider.addEventListener('input', function() {
            // Mettre à jour le contenu du span avec la valeur du slider
            editAvancementValue.textContent = editSlider.value + '%';
        });
    </script>
</body>
</html>



