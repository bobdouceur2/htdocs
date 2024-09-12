<?php
session_start();

// Vérifier si l'utilisateur est connecté et a les droits administratifs
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

require_once 'db_connection.php';

// Récupérer toutes les colonnes dynamiquement depuis la base de données
$columns_query = "SHOW COLUMNS FROM projets";
$columns_result = $conn->query($columns_query);

$columns = [];
while ($row = $columns_result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

// Requête pour récupérer les colonnes par défaut depuis la table default_columns (uniquement pour le popup)
$default_columns_query = "SELECT column_name FROM default_columns";
$default_columns_result = $conn->query($default_columns_query);

$default_columns = [];
while ($row = $default_columns_result->fetch_assoc()) {
    $default_columns[] = $row['column_name'];
}

// Requête pour récupérer toutes les lignes de la table "projets"
$data_query = "SELECT * FROM projets";
$data_result = $conn->query($data_query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mode Administrateur - Gestion de Projets</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-4">
        <h1>
            <a href="table1.php" style="text-decoration: none;">
                &#8592; <!-- Flèche gauche (HTML code) -->
            </a>
            Mode Administrateur
        </h1>
        <button id="addColumnBtn" class="styled-button">Ajouter une colonne</button>
        <button id="deleteColumnBtn" class="styled-button">Supprimer une colonne</button>
        <button id="manageColumnsBtn">Gérer les colonnes affichées par défaut dans le tableau principal</button>

        <!-- Affichage de toutes les colonnes dans le tableau -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <th><?php echo htmlspecialchars($column); ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th> <!-- Colonne pour les actions de modification/suppression -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $data_result->fetch_assoc()): ?>
                    <tr data-id="<?php echo htmlspecialchars($row['ID']); ?>">
                        <?php foreach ($columns as $column): ?>
                            <td contenteditable="true"><?php echo htmlspecialchars($row[$column]); ?></td>
                        <?php endforeach; ?>
                        <td>
                            <!-- Boutons d'actions pour modifier et supprimer -->
                            <button class="edit-btn" onclick="saveChanges(<?php echo $row['ID']; ?>)">Modifier</button>
                            <button class="delete-btn" onclick="deleteProject(<?php echo $row['ID']; ?>)">Supprimer</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Popup pour ajouter une colonne -->
    <div id="addColumnPopup" class="popup">
        <div class="popup-content">
            <span class="close-icon" id="closeAddColumnPopup">&times;</span>
            <h2>Ajouter une nouvelle colonne</h2>
            <form id="addColumnForm">
                <label for="columnName">Nom de la colonne:</label>
                <input type="text" id="columnName" name="columnName" required placeholder="Nom de la colonne">

                <label for="columnPosition">Position de la colonne:</label>
                <select id="columnPosition" name="columnPosition"></select>

                <button type="submit">Ajouter la colonne</button>
            </form>
        </div>
    </div>

    <!-- Popup pour gérer les colonnes affichées -->
    <div id="manageColumnsPopup" class="popup">
        <div class="popup-content">
            <span class="close-icon" id="closeManageColumnsPopup">&times;</span>
            <h2>Gérer les colonnes affichées par défaut</h2>
            <form id="manageColumnsForm">
                <!-- Les options de colonnes seront dynamiquement générées -->
                <div id="columnsCheckboxes">
                    <!-- Les cases à cocher seront générées ici via JavaScript -->
                </div>
                <button type="submit">Enregistrer les colonnes</button>
            </form>
        </div>
    </div>

    <!-- Popup pour supprimer une colonne -->
    <div id="deleteColumnPopup" class="popup">
        <div class="popup-content">
            <span class="close-icon" id="closeDeleteColumnPopup">&times;</span>
            <h2>Supprimer une colonne</h2>
            <form id="deleteColumnForm">
                <label for="deleteColumnName">Sélectionnez une colonne à supprimer:</label>
                <select id="deleteColumnName" name="deleteColumnName"></select>
                <button type="submit">Supprimer la colonne</button>
            </form>
        </div>
    </div>

    <div id="overlay" class="overlay"></div>

    <script>
        // Afficher le popup pour ajouter une colonne
        document.getElementById('addColumnBtn').addEventListener('click', function() {
            document.getElementById('addColumnPopup').classList.add('active');
            document.getElementById('overlay').classList.add('active');

            fetch('get_columns.php')
                .then(response => response.json())
                .then(data => {
                    const columnPosition = document.getElementById('columnPosition');
                    columnPosition.innerHTML = ''; // Vider la liste
                    data.forEach(column => {
                        const option = document.createElement('option');
                        option.value = column;
                        option.text = 'Après ' + column;
                        columnPosition.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur:', error));
        });

        // Afficher le popup pour supprimer une colonne
        document.getElementById('deleteColumnBtn').addEventListener('click', function() {
            document.getElementById('deleteColumnPopup').classList.add('active');
            document.getElementById('overlay').classList.add('active');

            fetch('get_columns.php')
                .then(response => response.json())
                .then(data => {
                    const deleteColumnSelect = document.getElementById('deleteColumnName');
                    deleteColumnSelect.innerHTML = ''; // Vider la liste
                    data.forEach(column => {
                        const option = document.createElement('option');
                        option.value = column;
                        option.text = column;
                        deleteColumnSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur:', error));
        });

        // Fermer les popups
        document.getElementById('closeAddColumnPopup').addEventListener('click', function() {
            document.getElementById('addColumnPopup').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
        });

        document.getElementById('closeDeleteColumnPopup').addEventListener('click', function() {
            document.getElementById('deleteColumnPopup').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
        });

        // Ajouter une colonne
        document.getElementById('addColumnForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const columnName = document.getElementById('columnName').value;
            const columnPosition = document.getElementById('columnPosition').value;

            fetch('add_column.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ columnName, columnPosition })
            })
            .then(response => response.text())
            .then(data => {
                alert('Colonne ajoutée avec succès');
                location.reload(); // Recharger la page après l'ajout de la colonne
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        });

        // Supprimer une colonne avec confirmation
        document.getElementById('deleteColumnForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const columnName = document.getElementById('deleteColumnName').value;

            if (confirm(`Êtes-vous sûr de vouloir supprimer la colonne "${columnName}" ? Cette action est irréversible.`)) {
                fetch('delete_column.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ columnName })
                })
                .then(response => response.text())
                .then(data => {
                    alert('Colonne supprimée avec succès');
                    location.reload(); // Recharger la page après la suppression de la colonne
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
            } else {
                // Si l'utilisateur annule, ne pas envoyer la requête
                console.log("Suppression annulée par l'utilisateur.");
            }
        });

        // Ouvrir le popup de gestion des colonnes affichées
        document.getElementById('manageColumnsBtn').addEventListener('click', function () {
            document.getElementById('manageColumnsPopup').classList.add('active');
            document.getElementById('overlay').classList.add('active');

            // Charger dynamiquement les colonnes et cases à cocher
            fetch('get_columns.php') // Récupère les colonnes et les colonnes par défaut depuis la base de données
                .then(response => response.json())
                .then(data => {
                    const columnsCheckboxes = document.getElementById('columnsCheckboxes');
                    columnsCheckboxes.innerHTML = ''; // Vider la liste actuelle

                    const allColumns = data.all_columns;
                    const defaultColumns = data.default_columns;

                    allColumns.forEach(column => {
                        const checkboxLabel = document.createElement('label');
                        checkboxLabel.classList.add('custom-checkbox');

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'columns[]';
                        checkbox.value = column;

                        // Cocher la case si la colonne fait partie des colonnes par défaut
                        if (defaultColumns.includes(column)) {
                            checkbox.checked = true;
                        }

                        const checkmark = document.createElement('span');
                        checkmark.classList.add('checkmark');

                        checkboxLabel.appendChild(checkbox);
                        checkboxLabel.appendChild(checkmark);
                        checkboxLabel.appendChild(document.createTextNode(column));

                        columnsCheckboxes.appendChild(checkboxLabel);
                    });
                })
                .catch(error => console.error('Erreur lors de la récupération des colonnes:', error));
        });

        // Fermer le popup
        document.getElementById('closeManageColumnsPopup').addEventListener('click', function () {
            document.getElementById('manageColumnsPopup').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
        });

        // Soumettre le formulaire et mettre à jour les colonnes affichées
        document.getElementById('manageColumnsForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this); // Créer un objet FormData contenant les données du formulaire

            fetch('update_columns.php', { // Endpoint pour mettre à jour les colonnes par défaut
                method: 'POST',
                body: formData // Envoyer les données du formulaire en tant que FormData
            })
            .then(response => response.text())
            .then(data => {
                alert('OUAAAAARRRRGGG');
                location.reload(); // Recharger la page après la mise à jour
            })
            .catch(error => console.error('Erreur lors de la mise à jour des colonnes:', error));
        });


    </script>
</body>
</html>
