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
                <i class="fas fa-search search-icon" onclick="clearSearch()"></i>
                <input type="text" id="searchInput" class="form-control mb-2" placeholder="Rechercher...">
            </div>
        </div>

        <div class="admin-header-container" onclick="logoutUser()">
            <div class="admin-header">
                <i class="fas fa-user"></i>
                <div class="userid"><?php echo htmlspecialchars($userId); ?></div>
            </div>
        </div>
    </header>




    

    <div class="main-container">
        <!-- Nouvelle colonne à gauche -->
        <div class="sidebar-container">
            <div class="sidebar card">
                <!-- Bouton pour agrandir/réduire la sidebar -->
                <button class="modern-button icon-expand" onclick="toggleSidebarExpansion()">
                    <i class="fas fa-expand"></i>
                    <span class="button-text"></span>
                </button>

                <!-- Bouton "Ajouter" existant -->
                <button class="modern-button icon-button" onclick="openPopupForm()">
                    <i class="fas fa-plus-circle"></i>
                    <span class="button-text">Ajouter</span>
                </button>

                <!-- Bouton Visualisation -->
                <button class="modern-button icon-button" onclick="openVisualizationPopUp()">
                    <i class="fas fa-chart-line"></i>
                    <span class="button-text">Visualisation</span>
                </button>

                <!-- Popup Form pour la Visualisation -->
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

                <!-- Scripts JavaScript associés -->
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

                <!-- Autres boutons de la sidebar -->
                <button class="modern-button settings-button" onclick="openSettingsPopup()">
                    <i class="fas fa-cog"></i> 
                    <span class="button-text">Réglages</span>
                </button>

                <button class="modern-button icon-button" onclick="openUploadPopup()">
                    <i class="fas fa-upload"></i>
                    <span class="button-text">Upload</span>
                </button>

                <button class="modern-button icon-button" onclick="window.location.href='view_files.php'">
                    <i class="fas fa-folder-open"></i>
                    <span class="button-text">Voir les fichiers</span>
                </button>
            </div> <!-- Fin de .sidebar.card -->
        </div> <!-- Fin de .sidebar-container -->

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
                    <h3>Tableau de Projets</h3>
                    <div id="tableContainer">
                        <?php require_once 'fetch_data.php'; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne à droite -->
        <div class="right-sidebar" id="rightSidebar">
            <button class="btn btn-primary btn-custom" onclick="openEditPopupForm()" style="display: flex;">Modifier le Projet</button>
            <button class="btn btn-primary btn-custom" onclick="deleteSelectedRow()" style="display: flex;">Supprimer le Projet</button>
        </div>
    </div>

    <div id="popupForm" class="popup-form">
        <div class="popup-content">
            <span class="close" onclick="closePopupForm()">&times;</span>
            <h2>Ajouter un Nouveau Projet</h2>
            <form id="addRowForm" class="form-container">
                <!-- ID du projet (rempli automatiquement) -->
                <label for="id"><b>ID</b></label>
                <input type="number" id="id" placeholder="Entrer l'ID" name="id" required value="<?php echo $nextId; ?>">
                <br>

                <!-- Champ pour l'intitulé -->
                <label for="intitule"><b>Intitulé</b></label>
                <input type="text" id="intitule" placeholder="Entrer l'intitulé" name="intitule" required>
                <br>

                <!-- Champ pour la description du problème -->
                <label for="description_probleme"><b>Description du Problème</b></label>
                <textarea id="description_probleme" placeholder="Décrire le problème" name="description_probleme" required></textarea>
                <br>

                <!-- Champ pour les objectifs opérationnels -->
                <label for="objectifs_operationnels"><b>Objectifs Opérationnels</b></label>
                <textarea id="objectifs_operationnels" placeholder="Décrire les objectifs opérationnels" name="objectifs_operationnels" required></textarea>
                <br>

                <!-- Champ pour la date de début -->
                <label for="datededebut"><b>Date de début</b></label>
                <input type="date" id="datededebut" name="datededebut" required>
                <br>

                <!-- Champ pour la date de fin -->
                <label for="datedefin"><b>Date de fin</b></label>
                <input type="date" id="datedefin" name="datedefin" required>
                <br>

                <!-- Section pour ajouter les dates jalons -->
                <div id="datesJalonContainer">
                    <label><b>Dates Jalon</b></label>
                    <div class="dates-jalon-entry">
                        <input type="date" name="jalon_dates[]" placeholder="Date Jalon">
                        <input type="text" name="jalon_texts[]" placeholder="Description">
                        <button type="button" onclick="addDateJalonEntry()">Ajouter une Date Jalon</button>
                    </div>
                </div>
                <br>

                <!-- Champ pour l'avancement -->
                <label for="avancement"><b>Avancement</b></label>
                <input type="range" min="0" max="100" value="0" class="slider" id="avancement" name="avancement">
                <span id="avancementValue">0%</span>
                <br>

                <!-- Champ pour l'équipe -->
                <label for="equipe"><b>Équipe</b></label>
                <input type="text" id="equipe" placeholder="Entrer l'équipe" name="equipe" required>
                <br>

                <!-- Bouton pour ajouter le projet -->
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
            <br><br>
            <!-- Ajout des boutons pour afficher les projets -->
            <button class="btn btn-primary" onclick="showAllProjects()">
                <i class="fas fa-tasks"></i> 
                <span>Afficher tous les projets</span>
            </button>
            <br><br>
            <button class="btn btn-secondary" onclick="showMyProjects()">
                <i class="fas fa-user"></i> 
                <span>Afficher uniquement les projets me concernant</span>
            </button>
            <br><br>
            
            <!-- Bouton existant pour le mode administrateur -->
            <button class="btn btn-primary" onclick="goToAdminMode()">Mode Administrateur</button>
        </div>
    </div>

    <div id="editPopupForm" class="popup-form">
        <div class="popup-content">
            <span class="close" onclick="closeEditPopupForm()">&times;</span>
            <h2>Modifier une ligne</h2>
            <form id="editRowForm" class="form-container">
                <input type="hidden" id="originalId" name="original_id"> <!-- Champ caché pour l'ID original -->

                <label for="editId"><b>ID</b></label>
                <input type="text" id="editId" name="id" readonly>
                <br>

                <label for="editIntitule"><b>Intitulé</b></label>
                <input type="text" id="editIntitule" name="intitule" required>
                <br>

                <label for="editDescriptionProbleme"><b>Description du Problème</b></label>
                <input type="text" id="editDescriptionProbleme" name="description_probleme" required>
                <br>

                <label for="editObjectifsOperationnels"><b>Objectifs Opérationnels</b></label>
                <input type="text" id="editObjectifsOperationnels" name="objectifs_operationnels" required>
                <br>

                <label for="editDatededebut"><b>Date de début</b></label>
                <input type="date" id="editDatededebut" name="datededebut" required>
                <br>

                <label for="editDatedefin"><b>Date de fin</b></label>
                <input type="date" id="editDatedefin" name="datedefin" required>
                <br>

                <!-- Nouvelle section pour Dates Jalon -->
                <div id="editDatesJalonContainer">
                    <label><b>Dates Jalon</b></label>
                </div>
                <br>
                
                <button type="button" onclick="addEditDateJalonEntry()">Ajouter une Date Jalon</button>
                <br><br>

                <label for="editAvancement"><b>Avancement</b></label>
                <input type="range" min="0" max="100" id="editAvancement" name="avancement">
                <span id="editAvancementValue">0%</span>
                <br><br>

                <label for="editEquipe"><b>Équipe</b></label>
                <input type="text" id="editEquipe" name="equipe" required>
                <br>

                <!-- Les champs suivants sont masqués par défaut, mais peuvent être activés via le formulaire de réglages -->
                <div class="hidden-fields">
                    <label for="editObjectifs"><b>Objectifs</b></label>
                    <input type="text" id="editObjectifs" name="objectifs">
                    <br>

                    <label for="editParticipants"><b>Participants</b></label>
                    <input type="text" id="editParticipants" name="participants">
                    <br>

                    <label for="editLevier"><b>Levier</b></label>
                    <select id="editLevier" name="levier">
                        <option value="">Sélectionner un levier</option>
                        <?php include 'levier_options.php'; ?>
                    </select>
                    <br>

                    <label for="editLocalisation"><b>Localisation</b></label>
                    <input type="text" id="editLocalisation" name="localisation">
                    <br>

                    <label for="editPerimetre"><b>Périmètre</b></label>
                    <input type="text" id="editPerimetre" name="perimetre">
                    <br>

                    <label for="editPlanning"><b>Planning</b></label>
                    <input type="text" id="editPlanning" name="planning">
                    <br>
                </div>

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
            // Remplir les champs existants
            document.getElementById('editId').value = rowData.id;
            document.getElementById('editIntitule').value = rowData.intitule;
            document.getElementById('editDescriptionProbleme').value = rowData.description_probleme;
            document.getElementById('editObjectifsOperationnels').value = rowData.objectifs_operationnels;
            document.getElementById('editDatededebut').value = rowData.datededebut;
            document.getElementById('editDatedefin').value = rowData.datedefin;
            document.getElementById('editAvancement').value = rowData.avancement;
            document.getElementById('editAvancementValue').innerText = rowData.avancement + '%';
            document.getElementById('editEquipe').value = rowData.equipe;
            document.getElementById('editParticipants').value = rowData.participants;
            document.getElementById('editLevier').value = rowData.levier;
            document.getElementById('editLocalisation').value = rowData.localisation;
            document.getElementById('editPerimetre').value = rowData.perimetre;
            document.getElementById('editPlanning').value = rowData.planning;

            // Effacer les anciennes entrées de dates jalon
            const jalonContainer = document.getElementById('editDatesJalonContainer');
            jalonContainer.innerHTML = `<label><b>Dates Jalon</b></label>`; // Réinitialiser le conteneur

            // Charger les dates jalon existantes
            if (rowData.dates_jalon) {
                const jalonData = JSON.parse(rowData.dates_jalon); // Supposons que les dates jalons sont stockées sous forme de chaîne JSON
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
            } else {
                // Si aucun jalon n'est disponible, on ajoute une entrée vide par défaut
                addEditDateJalonEntry();
            }
        }

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
            editAvancementValue.textContent = editSlider.value + '%';
        });
    </script>

    <div id="uploadPopupForm" class="popup-form" style="display: none;">
        <div class="popup-content">
            <span class="close" onclick="closeUploadPopup()">&times;</span>
            <h2>Upload de Fichier</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <label for="file">Choisir un fichier :</label>
                <input type="file" name="file" id="file" required>
                
                <label for="note">Note/Projet Associé :</label>
                <textarea name="note" id="note" rows="4" placeholder="Ajoutez une note ou un détail sur le fichier..." style="width: 100%; padding: 10px; border-radius: 5px; border: none; background-color: var(--form-input-background); color: var(--text-color);"></textarea>
                
                <button type="submit">Télécharger</button>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebarExpansion() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.getAttribute('data-expanded') === 'true') {
                sidebar.style.width = '70px'; // Réduire la largeur
                sidebar.setAttribute('data-expanded', 'false'); // Mettre à jour l'état
                sidebar.classList.remove('expanded'); // Retirer la classe d'expansion
            } else {
                sidebar.style.width = '250px'; // Agrandir la largeur
                sidebar.setAttribute('data-expanded', 'true'); // Mettre à jour l'état
                sidebar.classList.add('expanded'); // Ajouter la classe d'expansion
            }
        }
    </script>
</body>
</html>

