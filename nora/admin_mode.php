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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->

</head>
<body>

    <div class="container mt-4">
        <h1>
        <a href="table1.php" style="text-decoration: none;">
            <i class="fas fa-arrow-left"></i> <!-- Icône de flèche de Font Awesome -->
        </a>
            Mode Administrateur
        </h1>
        <button id="addColumnBtn" class="styled-button">Ajouter une colonne</button>
        <button id="deleteColumnBtn" class="styled-button">Supprimer une colonne</button>
        <button id="manageColumnsBtn">Gérer les colonnes affichées par défaut dans le tableau principal</button>

        <!-- Affichage de toutes les colonnes dans le tableau -->
        <div class="table-container">
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
    // Afficher le popup pour ajouter une colonne et charger dynamiquement les positions des colonnes
    document.getElementById('addColumnBtn').addEventListener('click', function() {
        document.getElementById('addColumnPopup').classList.add('active');
        document.getElementById('overlay').classList.add('active');

        fetch('get_columns.php')
            .then(response => response.json())
            .then(data => {
                const columnPosition = document.getElementById('columnPosition');
                columnPosition.innerHTML = ''; // Vider la liste actuelle

                const allColumns = data.all_columns; // Accéder au tableau des colonnes

                allColumns.forEach(column => {
                    // Créer une option "avant" la colonne
                    const optionBefore = document.createElement('option');
                    optionBefore.value = `before:${column}`;
                    optionBefore.text = `Avant ${column}`;
                    columnPosition.appendChild(optionBefore);

                    // Créer une option "après" la colonne
                    const optionAfter = document.createElement('option');
                    optionAfter.value = `after:${column}`;
                    optionAfter.text = `Après ${column}`;
                    columnPosition.appendChild(optionAfter);
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

                const allColumns = data.all_columns;

                allColumns.forEach(column => {
                    const option = document.createElement('option');
                    option.value = column;
                    option.text = column;
                    deleteColumnSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur:', error));
    });

    // Ouvrir le popup de gestion des colonnes affichées
    document.getElementById('manageColumnsBtn').addEventListener('click', function () {
        document.getElementById('manageColumnsPopup').classList.add('active');
        document.getElementById('overlay').classList.add('active');

        // Charger dynamiquement les colonnes et cases à cocher
        fetch('get_columns.php')
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

    // Fermer les popups
    document.getElementById('closeAddColumnPopup').addEventListener('click', function() {
        document.getElementById('addColumnPopup').classList.remove('active');
        document.getElementById('overlay').classList.remove('active');
    });

    document.getElementById('closeDeleteColumnPopup').addEventListener('click', function() {
        document.getElementById('deleteColumnPopup').classList.remove('active');
        document.getElementById('overlay').classList.remove('active');
    });

    // Fermer le popup de gestion des colonnes
    document.getElementById('closeManageColumnsPopup').addEventListener('click', function () {
        document.getElementById('manageColumnsPopup').classList.remove('active');
        document.getElementById('overlay').classList.remove('active');
    });

    // Ajouter une colonne via le formulaire
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
            console.log("Suppression annulée par l'utilisateur.");
        }
    });

    // Soumettre le formulaire et mettre à jour les colonnes affichées
    document.getElementById('manageColumnsForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('update_columns.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert('Colonnes mises à jour avec succès');
            location.reload();
        })
        .catch(error => console.error('Erreur lors de la mise à jour des colonnes:', error));
    });

    // Sauvegarder les modifications apportées aux projets
    function saveChanges(id) {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        const columns = row.querySelectorAll('td[contenteditable="true"]');
        let data = { id: id };

        columns.forEach((column, index) => {
            const columnName = document.querySelector(`th:nth-child(${index + 1})`).innerText.trim();
            data[columnName] = column.innerText.trim();
        });

        fetch('update_project.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.text())
        .then(result => {
            alert(result);
            console.log(result);
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    }
</script>


</body>
</html>
