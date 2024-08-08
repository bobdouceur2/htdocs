<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Métadonnées et ressources -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Lien vers la feuille de style CSS externe -->
    <link rel="stylesheet" href="styles.css">
    <!-- Lien vers une police de caractères Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
</head>
<body>
    <!-- En-tête du tableau de bord -->
    <header>
        <!-- Champ de recherche -->
        <input type="text" class="search-bar" placeholder="Rechercher...">
        
        <!-- Section du profil utilisateur -->
        <div class="user-profile">
            <img src="user.png" alt="User Icon">
            <span>Utilisateur</span> 
        </div>
        
        <!-- Espace pour une icône ou un texte supplémentaire (actuellement commenté) -->
        <div class="wrapper">
            <!--
	            <svg>
                    <text x="80" y="50%" text-anchor="start" dominant-baseline="middle">
			        SE 
		            </text>
	            </svg>
            -->
        </div>
    </header>
    
    <!-- Barre latérale de navigation -->
    <div class="sidebar">
        <!-- Logo de l'entreprise -->
        <img src="safran_logo.png" alt="Safran Logo" class="logo">
        
        <!-- Icônes de navigation -->
        <div class="icon active">
            <div class="outer-rectangle">
                <div class="inner-rectangle"></div>
            </div>
            <img src="homeb.png" alt="Home Icon">
        </div>
        <div class="icon">
            <img src="dashboard.png" alt="Dashboard Icon">
        </div>
        <div class="icon">
            <img src="settings.png" alt="Settings Icon" class="settings-icon">
        </div>
        <div class="icon bottom-icon">
            <img src="disconnect.png" alt="Disconnect Icon">
        </div>
    </div>
    
    <!-- Conteneur principal -->
    <div class="main-container">
        <!-- Cartes d'information --> 
        <div class="card card-top"></div>
        <div class="card card-right">
            <div class="buttonsContainer">
                <!-- Boutons pour ajouter ou modifier des lignes -->
                <button onclick="toggleAddForm()" class="button">Ajouter une ligne</button>
                <button onclick="toggleEditForm()" class="button">Modifier une ligne</button>
            </div>

            <!-- Formulaire d'ajout de ligne (caché par défaut) -->
            <div id="addFormContainer" class="formContainer" style="display: none;">
                <h2>Ajouter une nouvelle ligne :</h2>
                <form id="addRowForm">
                    <input type="text" name="intitule" placeholder="Intitulé" required /><br>
                    <input type="text" name="objectifs" placeholder="Objectifs" required /><br>
                    <input type="date" name="datededebut" placeholder="Date de début" required /><br>
                    <input type="date" name="datedefin" placeholder="Date de fin" required /><br>
                    <input type="range" id="addAvancement" name="avancement" min="0" max="100" value="0" oninput="avancementValueDisplay('addAvancement', this.value)" style="width: 100%;">
                    <span id="addAvancementValue">0%</span><br>
                    <select name="levier" id="levier" required>
                        <option value="">Sélectionnez un levier</option>
                        <!-- Ajouter vos options ici -->
                    </select><br>
                    <button type="button" onclick="addRow()" class="button">Ajouter</button>
                </form>
            </div>

            <!-- Formulaire de modification de ligne (caché par défaut) -->
            <div id="editFormContainer" class="formContainer" style="display: none;">
                <h2>Modifier une ligne :</h2>
                <form id="editRowForm">
                    <input type="number" id="editRowId" name="id" placeholder="Entrez l'ID de la ligne à modifier" required /><br>
                    <label for="editDatedefin">Date de fin :</label>
                    <input type="date" id="editDatedefin" name="datedefin" required /><br>
                    <label for="editAvancement">Avancement :</label>
                    <input type="range" id="editAvancement" name="avancement" min="0" max="100" value="0" oninput="avancementValueDisplay('editAvancement', this.value)" style="width: 100%;">
                    <span id="editAvancementValue">0%</span><br>
                    <button type="submit" onclick="editRow()" class="button">Modifier</button>
                </form>
            </div>
        </div>
        
        <!-- Section pour afficher un tableau dynamique -->
        <div class="card card-large table-container">
            <!-- Insertion du tableau généré par PHP -->
            <?php require_once 'fetch_data.php'; ?> 
        </div>
    </div>

    <!-- Inclusion du fichier de connexion à la base de données -->
    <?php require_once 'db_connection.php'; ?>

    <!-- Lien vers le fichier JavaScript externe -->
    <script src="functions.js"></script>
</body>
</html>


