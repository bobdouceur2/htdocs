<?php
session_start();
if (!isset($_SESSION['userId'])) {
  
    header('Location: index.php');
    exit();
}

$userId = $_SESSION['userId'];

require_once 'db_connection.php';

// Vérifier la connexion à la base de données
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Récupérer le plus grand ID actuel et ajouter 1 pour obtenir le prochain ID disponible
$query = "SELECT MAX(ID) as max_id FROM projets";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $nextId = $row['max_id'] + 1;
} else {
    $nextId = 1; // Par défaut, commencer à 1 si la table est vide ou en cas d'erreur
}








?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de Projets</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="gantt.css"> <!-- Changer calendar.css en gantt.css pour le diagramme de Gantt -->
    <script src="functions.js" defer></script>
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
    
        <a href="table1.php">
            <img src="logosafran.png" alt="Logo Safran" id="logoSafran" />
        </a>
    



        <div class="form-group">
                <div class="search-container">
                    <i class="fas fa-search search-icon" onclick="toggleSearch()"></i>
                    <input type="text" id="searchInput" class="form-control mb-2" placeholder="Rechercher...">
                </div>
        </div>



        <button class="modern-button" onclick="showAllProjects()">
            <i class="fas fa-tasks"></i> 
            <span class="button-text">Afficher tous les projets</span>
        </button>

        <button class="modern-button secondary" onclick="showMyProjects()">
            <i class="fas fa-user"></i> 
            <span class="button-text">Afficher uniquement les projets me concernant</span>
        </button>




        <div class="admin-header">
            <i class="fas fa-user"></i>
            <div class="userid">  <?php echo htmlspecialchars($userId); ?></div>
        </div>



        
    </header>



    <div class="main-container">
        <!-- Nouvelle colonne à gauche -->
        <div class="sidebar">
            <!-- Regroupement des éléments de recherche, filtrage et tri -->
            

            <button class="modern-button icon-button" onclick="openPopupForm()">
                <i class="fas fa-plus-circle"></i>
                <span class="button-text">Ajouter</span>
            </button>

            <button class="modern-button icon-button" onclick="openVisualizationPopUp()">
                <i class="fas fa-chart-line"></i>
                <span class="button-text">Visualisation</span>
            </button>


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
            <!--
            <div class="form-group">
                
                <form id="filterForm" class="form-inline">
                    <select id="levierSelect" name="levier" class="form-control mb-2" style="width: 100%;" onchange="filterByLevier()">
                        <option value="">Filtrer par Levier</option>
                        <?php /*include 'levier_options.php'; */ ?>
                    </select>
                </form>
            </div>
            -->

            <!-- <div class="form-group">
                
                <form id="sortForm" class="form-inline">
                    <select id="sortSelect" name="sort" class="form-control mb-2" style="width: 100%;" onchange="sortProjects()">
                        <option value="">Trier par</option>
                        <option value="dateAsc">Date de début croissante</option>
                        <option value="dateDesc">Date de début décroissante</option>
                        <option value="avancementAsc">Avancement croissant</option>
                        <option value="avancementDesc">Avancement décroissant</option>
                    </select>
                </form>
            </div> -->

            <button class="modern-button settings-button" onclick="openSettingsPopup()">
                <i class="fas fa-cog"></i> 
                <span class="button-text">Réglages</span>
            </button>

            <button class="modern-button logout-button" onclick="logoutUser()">
                <i class="fas fa-sign-out-alt"></i> 
                <span class="button-text">Déconnexion</span>
            </button>

            


            

           





            
        </div>

        <!-- Conteneur de contenu principal -->
        <div class="content" id="mainContent">




    <div class="card mb-4">    
           

        <div class="card-body">    
            <h3>Diagramme de Gantt</h3>
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
        </div>
    </div>





<div class="card mb-4">    
           

        <div class="card-body"> 
        <h3>Tableau de Projet</h3>

            <div id="tableContainer">
                <?php require_once 'fetch_data.php'; ?>
            </div>
        </div>
</div>




</div>


        <!-- Colonne à droite -->
        <div class="right-sidebar" id="rightSidebar">
            <button class="btn btn-primary btn-custom" onclick="openEditPopupForm()">Modifier le Projet</button>
            <button class="btn btn-danger btn-custom" onclick="deleteSelectedRow()">Supprimer le Projet</button>
        </div>
    </div>

    <div id="popupForm" class="popup-form">
    <div class="popup-content">
        <span class="close" onclick="closePopupForm()">&times;</span>
        <h2>Ajouter un Nouveau Projet</h2>
        <form id="addRowForm" class="form-container">
            <br><br>
            <label for="id"><b>ID</b></label>
            <input type="number" id="id" placeholder="Entrer l'ID" name="id" required value="<?php echo $nextId; ?>">
            <br><br>
            <label for="intitule"><b>Intitulé</b></label>
            <input type="text" id="intitule" placeholder="Entrer l'intitulé" name="intitule" required>
            <br><br>
            <label for="objectifs"><b>Objectifs</b></label>
            <input type="text" id="objectifs" placeholder="Entrer les objectifs" name="objectifs" required>
            <br><br>
            <label for="datededebut"><b>Date de début</b></label>
            <input type="date" id="datededebut" name="datededebut" required>
            <br><br>
            <label for="datedefin"><b>Date de fin</b></label>
            <input type="date" id="datedefin" name="datedefin" required>
            <br><br>
            <label for="avancement"><b>Avancement</b></label>
            <input type="range" min="0" max="100" value="0" class="slider" id="avancement" name="avancement">
            <span id="avancementValue">0%</span>

            <br><br>

            <label for="participants"><b>Participants</b></label>
            <input type="text" id="participants" placeholder="Entrer les participants" name="participants" required>
            <br><br>
            <label for="levier"><b>Levier</b></label>
            <select id="levier" name="levier" required>
                <option value="">Sélectionner un levier</option>
                <?php include 'levier_options.php'; ?>
            </select>
            <br><br>

            <label for="localisation"><b>Localisation</b></label>
            <input type="text" id="localisation" placeholder="Entrer la localisation" name="localisation" required>
            <br><br>
            <!-- Nouvelle section pour Dates Jalon -->
            <div id="datesJalonContainer">
                <label><b>Dates Jalon</b></label>
                <div class="dates-jalon-entry">
                    <input type="date" name="jalon_dates[]" placeholder="Date Jalon">
                    <input type="text" name="jalon_texts[]" placeholder="Description">
                    <button type="button" onclick="addDateJalonEntry()">Ajouter une Date Jalon</button>
                </div>
            </div>

            <br><br>
            <br><br>

            <button type="button" onclick="addRow()">Ajouter un nouveau Projet</button>
        </form>
        <div id="message" style="display: none;"></div>
    </div>


</div>

<script>
    // Fonction pour mettre à jour la valeur du champ Avancement
    document.getElementById('avancement').addEventListener('input', function() {
        document.getElementById('avancementValue').textContent = this.value + '%';
    });

    // Fonction pour ajouter dynamiquement des champs de date jalon
    function addDateJalonEntry() {
        var container = document.getElementById('datesJalonContainer');
        var entry = document.createElement('div');
        entry.className = 'dates-jalon-entry';
        entry.innerHTML = `
            <input type="date" name="jalon_dates[]" placeholder="Date Jalon">
            <input type="text" name="jalon_texts[]" placeholder="Description">
            <button type="button" onclick="removeDateJalonEntry(this)">Supprimer</button>
        `;
        container.appendChild(entry);
    }

    // Fonction pour supprimer une entrée de date jalon
    function removeDateJalonEntry(button) {
        var entry = button.parentNode;
        entry.parentNode.removeChild(entry);
    }
</script>


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

            <label for="editLocalisation"><b>Localisation</b></label>
            <input type="text" id="editLocalisation" name="localisation" required>

            <!-- Nouvelle section pour Dates Jalon -->
            <div id="editDatesJalonContainer">
                <label><b>Dates Jalon</b></label>
                <div class="dates-jalon-entry">
                    <!-- Exemple d'entrée jalon; ces entrées seront générées dynamiquement avec JavaScript -->
                    <input type="date" name="edit_jalon_dates[]" placeholder="Date Jalon">
                    <input type="text" name="edit_jalon_texts[]" placeholder="Description">
                    <button type="button" onclick="addEditDateJalonEntry()">Ajouter une Date Jalon</button>
                </div>
            </div>

            <br><br>
            <button type="button" onclick="saveEditedRow()">Sauvegarder</button>
        </form>
    </div>
</div>

<script>
    function addEditDateJalonEntry() {
        const container = document.getElementById('editDatesJalonContainer');
        const newEntry = document.createElement('div');
        newEntry.classList.add('dates-jalon-entry');
        
        newEntry.innerHTML = `
            <input type="date" name="edit_jalon_dates[]" placeholder="Date Jalon">
            <input type="text" name="edit_jalon_texts[]" placeholder="Description">
            <button type="button" onclick="removeDateJalonEntry(this)">Supprimer</button>
        `;

        container.appendChild(newEntry);
    }

    function removeDateJalonEntry(button) {
        button.parentElement.remove();
    }

    function loadExistingData(rowData) {
        // Cette fonction devrait charger les données existantes dans le formulaire d'édition.
        // 'rowData' est un objet contenant les données de la ligne sélectionnée.

        document.getElementById('editId').value = rowData.id;
        document.getElementById('editIntitule').value = rowData.intitule;
        document.getElementById('editObjectifs').value = rowData.objectifs;
        document.getElementById('editDatededebut').value = rowData.datededebut;
        document.getElementById('editDatedefin').value = rowData.datedefin;
        document.getElementById('editAvancement').value = rowData.avancement;
        document.getElementById('editAvancementValue').innerText = rowData.avancement + '%';
        document.getElementById('editParticipants').value = rowData.participants;
        document.getElementById('editLevier').value = rowData.levier;
        document.getElementById('editLocalisation').value = rowData.localisation;

        // Effacer les anciennes entrées de dates jalon
        const jalonContainer = document.getElementById('editDatesJalonContainer');
        jalonContainer.innerHTML = `<label><b>Dates Jalon</b></label>`; // Réinitialiser le conteneur

        // Charger les dates jalon existantes
        if (rowData.dates_jalon) {
            const jalonData = JSON.parse(rowData.dates_jalon);
            jalonData.forEach(jalon => {
                const newEntry = document.createElement('div');
                newEntry.classList.add('dates-jalon-entry');
                newEntry.innerHTML = `
                    <input type="date" name="edit_jalon_dates[]" value="${jalon.date}">
                    <input type="text" name="edit_jalon_texts[]" value="${jalon.text}">
                    <button type="button" onclick="removeDateJalonEntry(this)">Supprimer</button>
                `;
                jalonContainer.appendChild(newEntry);
            });
        }
    }
</script>


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
    <
</body>
</html>
