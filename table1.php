<?php
session_start();
if (!isset($_SESSION['userId'])) {
    // Rediriger vers index.php si l'identifiant n'est pas défini
    header('Location: index.php');
    exit();
}
$userId = $_SESSION['userId'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de Projets</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.css">
    <script src="functions.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Ajout des balises de lien pour charger la police depuis Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
        /* Styles existants ici */
        .main-container {
            display: flex;
            align-items: flex-start;
        }
        .sidebar {
            width: 300px; /* Ajustez la largeur selon vos besoins */
            padding: 10px;
            display: grid;
            gap: 5px;
            justify-items: center;
        }
        .content {
            flex-grow: 1;
            padding: 10px;
        }
        .sidebar .btn, .sidebar select {
            width: 100%;
            margin-bottom: 10px;
        }
        .small-dropdown {
            max-width: 260px; /* Ajustez cette valeur selon vos besoins */
        }
        .form-group {
            margin-bottom: 10px;
        }
        .admin-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px;
            background-color: #121a45;
        }
        .admin-header .userid {
            margin-right: 20px;
            font-weight: bold;
        }
        .site-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #121a45;
        }
        #logoSafran {
            height: 50px; /* Ajustez selon la taille souhaitée */
        }
    </style>
</head>
<body>
    <header class="site-header">
        <img src="logo.png" alt="Logo Safran" id="logoSafran" />
        <div class="admin-header">
            <div class="userid">Utilisateur: <?php echo htmlspecialchars($userId); ?></div>
            <button onclick="location.href='login.php'" class="btn btn-outline-primary">Mode Administrateur</button>
        </div>
    </header>

    <?php require_once 'db_connection.php'; ?>

    <div class="main-container">
        <!-- Nouvelle colonne à gauche -->
        <div class="sidebar">
            <!-- Regroupement des éléments de recherche, filtrage et tri -->

            <div class="form-group">
                <!-- Barre de recherche universelle -->
                <form id="filterForm" class="form-inline">
                    <input type="text" id="searchInput" class="form-control mb-2" style="width: 100%;" placeholder="Rechercher...">
                </form>
            </div>

            <i class="fas fa-plus-circle add-icon" onclick="openPopupForm()"></i>
            <i class="fas fa-chart-line view-icon" onclick="openVisualization()" title="Visualisation"></i>

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
            

            
                

            
        </div>






        <!-- Conteneur de contenu principal -->
        <div class="content" id="mainContent">
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
            <input type="number" id="id" placeholder="Entrer l'ID" name="id" required>

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

            <i class="fas fa-plus-circle add-icon" onclick="addRow()"></i>
        </form>
        <div id="message" style="display: none;"></div>
    </div>
</div>

<script>
document.getElementById('avancement').addEventListener('input', function() {
    document.getElementById('avancementValue').textContent = this.value + '%';
});

function addRow() {
    var form = document.getElementById("addRowForm");
    var formData = new FormData(form);

    // Initialiser un tableau pour collecter les paramètres manquants
    var missingParams = [];

    // Vérifier chaque champ requis
    if (!formData.get('id')) missingParams.push('ID');
    if (!formData.get('intitule')) missingParams.push('Intitulé');
    if (!formData.get('objectifs')) missingParams.push('Objectifs');
    if (!formData.get('datededebut')) missingParams.push('Date de début');
    if (!formData.get('datedefin')) missingParams.push('Date de fin');
    if (!formData.get('avancement')) missingParams.push('Avancement');
    if (!formData.get('participants')) missingParams.push('Participants');
    if (!formData.get('levier')) missingParams.push('Levier');

    // Si des paramètres sont manquants, afficher un message d'erreur
    if (missingParams.length > 0) {
        alert("Les paramètres suivants sont manquants ou vides : " + missingParams.join(', '));
        return; // Arrêter l'exécution de la fonction si des paramètres sont manquants
    }

    // Si tous les champs requis sont remplis, envoyer la requête
    fetch("insert_row.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        // Afficher le message de succès ou d'erreur dans le div #message
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        if (data.includes("succès")) {
            messageDiv.style.color = 'green';
            closePopupForm();
            refreshTableData();
        } else {
            messageDiv.style.color = 'red';
        }
        messageDiv.innerText = data;
    })
    .catch(error => {
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.style.color = 'red';
        messageDiv.innerText = 'Erreur : ' + error;
    });
}
</script>


<script>
document.getElementById('avancement').addEventListener('input', function() {
    document.getElementById('avancementValue').textContent = this.value + '%';
});
</script>


    <script>
        // Ajout de l'événement pour la touche Entrée sur le champ de recherche
        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Empêche le comportement par défaut du formulaire
                searchTable();
            }
        });

       

        // Sélectionnez le slider et l'élément span pour la valeur d'avancement
        const slider = document.getElementById('avancement');
        const avancementValue = document.getElementById('avancementValue');

        

        // Écoutez les changements de valeur du slider
        slider.addEventListener('input', function() {
            // Mettez à jour le contenu du span avec la valeur du slider
            avancementValue.textContent = slider.value + '%';
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






    </body>
    </html>

    <script>
        // Ajout de l'événement pour la touche Entrée sur le champ de recherche
        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Empêche le comportement par défaut du formulaire
                searchTable();
            }
        });

        function showAllProjects() {
            // Recharge la page sans filtre ni tri
            window.location.href = window.location.pathname + "?showAll=true";
        }

        // Sélectionner les éléments du slider et du span associés à l'avancement
        const editSlider = document.getElementById('editAvancement');
        const editAvancementValue = document.getElementById('editAvancementValue');

        // Écouter les changements de valeur du slider
        editSlider.addEventListener('input', function() {
            // Mettre à jour le contenu du span avec la valeur du slider
            editAvancementValue.textContent = editSlider.value + '%';
        });

        // Fonction pour ouvrir une nouvelle fenêtre vide
        function openVisualization() {
            window.open('', '_blank', 'width=800,height=600');
        }
    </script>
</body>
</html>