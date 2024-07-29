<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <input type="text" class="search-bar" placeholder="Rechercher...">
        <!--
        <div class="icon">
            <img src="recherche.png" alt="Recherche Icon">
        </div> -->
        <div class="user-profile">
            <img src="user.png" alt="User Icon">
            <span>Utilisateur</span> 
        </div>
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
    
    <div class="sidebar">
        <img src="safran_logo.png" alt="Safran Logo" class="logo">
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
    
    <div class="main-container">
        <div class="card card-top"></div>
        <div class="card card-right">
            <div class="buttonsContainer">
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
        <div class="card card-large table-container">
            <?php require_once 'fetch_data.php'; ?> <!-- Insérer le tableau ici -->
        </div>
    </div>

    <?php
    // Inclusion du fichier de connexion à la base de données.
    require_once 'db_connection.php';
    ?>

    <script>
    // JavaScript pour gérer les actions du formulaire
    function toggleAddForm() {
        var addFormContainer = document.getElementById("addFormContainer");
        var editFormContainer = document.getElementById("editFormContainer");

        addFormContainer.style.display = addFormContainer.style.display === "none" ? "block" : "none";
        // Assurez-vous de cacher le formulaire de modification si le formulaire d'ajout est affiché
        if (addFormContainer.style.display === "block") {
            editFormContainer.style.display = "none";
        }
    }

    function toggleEditForm() {
        var editFormContainer = document.getElementById("editFormContainer");
        var addFormContainer = document.getElementById("addFormContainer");

        editFormContainer.style.display = editFormContainer.style.display === "none" ? "block" : "none";
        
        // Cacher le formulaire d'ajout si le formulaire de modification est affiché
        if (editFormContainer.style.display === "block") {
            addFormContainer.style.display = "none";
        }
    }

    function getEditFormData() {
        var id = document.getElementById('editRowId').value;
        if(id) {
            fetch("get_row_data.php", {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('editIntitule').value = data.Intitule;
                document.getElementById('editObjectifs').value = data.Objectifs;
                document.getElementById('editDatededebut').value = data.DateDeDebut;
                document.getElementById('editDatedefin').value = data.DateDeFin;
                document.getElementById('editAvancement').value = data.Avancement;
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function addRow() {
        var formData = new FormData(document.getElementById("addRowForm"));
        fetch("insert_row.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload(); // Rechargez la page pour voir les changements
        })
        .catch(error => console.error('Error:', error));
    }

    function deleteById() {
        var id = document.getElementById('deleteId').value;
        if (id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer la ligne avec l'ID " + id + " ?")) {
                fetch("delete_row.php", {
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'id=' + id
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Rechargez la page pour voir les changements
                })
                .catch(error => console.error('Error:', error));
            }
        } else {
            alert("Veuillez entrer un ID.");
        }
    }

    // Mettre à jour l'affichage de la valeur d'avancement en pourcentage
    function avancementValueDisplay(elementId, value) {
        var avancementPercentage = value + '%';
        document.getElementById(elementId + 'Value').textContent = avancementPercentage;
    }

    // Fonction pour modifier une ligne
    function editRow() {
        var formData = new FormData(document.getElementById("editRowForm"));
        fetch("update_row.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json()) // Assurez-vous que le serveur renvoie JSON
        .then(data => {
            // Mettez à jour la ligne modifiée dans le tableau
            var rowId = document.getElementById('editRowId').value;
            var editedRow = document.getElementById('row_' + rowId);
            editedRow.innerHTML = data; // Mettez à jour le contenu de la ligne avec les nouvelles données

            toggleEditForm(); // Cacher le formulaire de modification après la mise à jour
            alert("La ligne a été mise à jour avec succès !");

            // Récupérer la valeur de l'avancement et mettre à jour son affichage
            var avancementValue = document.getElementById('editAvancement').value;
            avancementValueDisplay(avancementValue, 'editAvancement');
        })
        .catch(error => console.error('Error:', error));
    }

    // Fonction pour rafraîchir les données du tableau
    function refreshTableData() {
        fetch("fetch_data.php") 
        .then(response => response.text())
        .then(html => {
            const tableContainer = document.getElementById("tableContainer");
            if(tableContainer) {
                tableContainer.innerHTML = html; // Met à jour le contenu du conteneur du tableau
            }
        })
        .catch(error => console.error('Erreur lors de la mise à jour des données du tableau:', error));
    }

    // Ajouter le gestionnaire d'événement pour l'icône des paramètres
    document.querySelector('.settings-icon').addEventListener('click', function() {
        location.href = 'login.php';
    });
    </script>

    <?php
    $conn->close();
    ?>
</body>
</html>
