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
                    <!-- BOUTON RECHERCHER <button type="button" onclick="searchTable()" class="btn btn-outline-primary mb-2" style="margin-left: auto; margin-right: auto;">Rechercher</button>-->
                </form>
            </div>

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
                <!-- Ajout du dropdown Trier par date -->
                <form id="sortForm" class="form-inline">
                    <select id="sortDateSelect" name="sortDate" class="form-control mb-2" style="width: 100%;" onchange="sortByDate()">
                        <option value="">Trier par Date</option>
                        <?php include 'trier_options.php'; ?>
                    </select>
                </form>
            </div>

            <button class="btn btn-primary btn-custom" onclick="showAllProjects()">Afficher tous les Projets</button>
            <button class="btn btn-secondary btn-custom" onclick="showMyProjects()">Afficher uniquement les projets me concernant</button>



        </div>

        <!-- Conteneur de contenu principal -->
        <div class="content">
            <div id="tableContainer">
                <?php require_once 'fetch_data.php'; ?>
            </div>

            <div class="buttonsContainer">
                <button onclick="openPopupForm()" class="button">Ajouter une ligne</button>
                <button onclick="toggleEditForm()" class="button">Modifier une ligne</button>
            </div>

            <div id="addFormContainer" class="formContainer" style="display: none;">
                <!-- Formulaires ajout et édition ici -->
            </div>

            <div id="editFormContainer" class="formContainer" style="display: none;">
                <!-- Formulaires ajout et édition ici -->
            </div>
        </div>
    </div>

    <div id="popupForm" class="popup-form">
        <div class="popup-content">
            <span class="close" onclick="closePopupForm()">&times;</span>
            <h2>Ajouter une nouvelle ligne</h2>
            <form id="addRowForm" class="form-container">
                <label for="intitule"><b>Intitulé</b></label>
                <input type="text" placeholder="Entrer l'intitulé" name="intitule" required>

                <label for="objectifs"><b>Objectifs</b></label>
                <input type="text" placeholder="Entrer les objectifs" name="objectifs" required>

                <label for="datededebut"><b>Date de début</b></label>
                <input type="date" name="datededebut" required>

                <label for="datedefin"><b>Date de fin</b></label>
                <input type="date" name="datedefin" required>

                <label for="avancement"><b>Avancement</b></label>
                <input type="range" min="0" max="100" value="0" class="slider" id="avancement" name="avancement">
                <span id="avancementValue">0%</span>

                <button type="button" onclick="addRow()">Ajouter</button>
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

        function showAllProjects() {
            // Recharge la page sans filtre ni tri
            window.location.href = window.location.pathname + "?showAll=true";
        }

        // Sélectionnez le slider et l'élément span pour la valeur d'avancement
        const slider = document.getElementById('avancement');
        const avancementValue = document.getElementById('avancementValue');

        // Écoutez les changements de valeur du slider
        slider.addEventListener('input', function() {
            // Mettez à jour le contenu du span avec la valeur du slider
            avancementValue.textContent = slider.value + '%';
        });
    </script>
</body>
</html>
