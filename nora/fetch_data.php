<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des projets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        :root {
            --background-color: #1b1c27;
            --text-color: #FFFFFF;
            --header-background: #252836;
            --button-background: #1F1D2B;
            --button-hover: #252836;
            --form-background: #252836;
            --form-input-background: #252836;
            --form-input-border: #343456;
            --table-background: #1F1D2B;
            --table-text-color: #FFFFFF;
            --table-header-background: #1F1D2B;
            --table-cell-border: #2c2c4600;
            --table-even-row-background: #0f112e;
            --filter-form-button-background: #1F1D2B;
            --filter-form-button-hover: #252836;
        }

        /* Styles pour les dates dépassées */
        .date-depassee {
            color: red;
        }

        /* Styles pour les colonnes masquées */
        .hidden-column {
            display: none;
        }

        /* Styles pour les checkboxes personnalisées */
        .custom-checkbox {
            position: relative;
            display: inline-block;
            width: 18px;
            height: 18px;
        }

        .custom-checkbox input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-checkbox .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 30px;
        }

        .custom-checkbox input:checked + .checkmark {
            background-color: #2196F3;
        }

        .custom-checkbox .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .custom-checkbox input:checked + .checkmark:after {
            display: block;
        }

        .custom-checkbox .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }

        a {
            color: white;
            text-decoration: none;
        }

        a:hover {
            color: #ddd;
        }

        th a {
            color: white;
        }

        .sort-icon {
            font-size: 12px;
            margin-left: 5px;
            color: white;
        }

        th, td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid var(--table-cell-border);
        }

        th:first-child,
        td:first-child {
            width: 75px; /* Largeur plus petite pour la colonne des checkboxes */
        }

        /* Styles pour le popup */
        .popup {
            display: none; /* Masqué par défaut */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: var(--form-background);
            padding: 20px;
            border: 2px solid var(--form-input-border);
            border-radius: 8px;
            z-index: 1000;
            width: 80%; /* Largeur du popup ajustée */
            max-width: 600px; /* Limite de la largeur maximale */
            max-height: 80vh; /* Limite de la hauteur maximale */
            overflow-y: auto; /* Ajout d'un défilement vertical si nécessaire */
        }

        .popup.active {
            display: block; /* Afficher lorsque actif */
        }

        .overlay {
            display: none; /* Masqué par défaut */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }

        .overlay.active {
            display: block; /* Afficher lorsque actif */
        }

        .close-popup {
            background-color: var(--button-background);
            border: none;
            color: var(--text-color);
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Style pour la carte contenant l'icône de bouton */
        .icon-card {
            position: absolute;
            right: 20px;
            top: 20px;
            background-color: var(--button-background);
            border: 1px solid var(--form-input-border);
            border-radius: 8px;
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Style pour l'icône de bouton à l'intérieur de la carte */
        .icon-button {
            cursor: pointer;
            font-size: 24px;
            color: var(--text-color);
            background: none;
            border: none;
            padding: 5px;
        }

        .icon-button:hover {
            color: #2196F3;
        }
    </style>
</head>
<body>

    <!-- Carte contenant l'icône de réglage placée à droite -->
    <div class="icon-card">
        <button id="openPopup" class="icon-button">
            <i class="fas fa-cog"></i> <!-- Utilisation de FontAwesome pour l'icône de réglage -->
        </button>
    </div>

    <!-- Overlay pour le popup -->
    <div id="overlay" class="overlay"></div>

    <!-- Popup pour sélectionner les colonnes à afficher -->
    <div id="columnPopup" class="popup">
        <form method="POST" id="columnSelector">
            <h3>Choisissez les colonnes à afficher :</h3>

            <?php
            require_once 'db_connection.php';

            // Récupérer toutes les colonnes de la table "projets"
            $query = "SHOW COLUMNS FROM projets";
            $result = $conn->query($query);

            // Afficher toutes les colonnes comme options dans le popup
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $column_name = $row['Field'];
                    $checked = in_array($column_name, ['Intitule', 'DescriptionProbleme', 'ObjectifsOperationnels', 'DateDeDebut', 'DateDeFin', 'dates_jalon', 'Avancement', 'Equipe']) ? 'checked' : '';
                    echo "<label><input type='checkbox' name='columns[]' value='$column_name' $checked> $column_name</label><br>";
                }
            }

            $result->free();
            ?>

            <button type="submit">Mettre à jour le tableau</button>
        </form>
        <button class="close-popup" id="closePopup">Fermer</button>
    </div>

    <?php
    // Vérifier quelles colonnes sont sélectionnées
    $selected_columns = isset($_POST['columns']) ? $_POST['columns'] : ['Intitule', 'DescriptionProbleme', 'ObjectifsOperationnels', 'DateDeDebut', 'DateDeFin', 'dates_jalon', 'Avancement', 'Equipe'];

    // Construire la requête SQL dynamique
    $columns_to_select = implode(", ", $selected_columns);
    $query = "SELECT $columns_to_select, ID FROM projets";  // Assurer que 'ID' est toujours sélectionné pour les checkboxes
    $stmt = $conn->prepare($query);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>
                <label class='custom-checkbox'>
                    <input type='checkbox' id='selectAll'>
                    <span class='checkmark'></span>
                </label>
              </th>";

        // Affichage dynamique des en-têtes de colonnes
        foreach ($selected_columns as $column) {
            echo "<th>" . htmlspecialchars($column) . "</th>";
        }

        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>
                    <label class='custom-checkbox'>
                        <input type='checkbox' class='rowCheckbox' data-id='" . htmlspecialchars($row['ID']) . "'>
                        <span class='checkmark'></span>
                    </label>
                  </td>";

            // Affichage dynamique des données des colonnes
            foreach ($selected_columns as $column) {
                if ($column == "dates_jalon") {
                    // Gestion spéciale pour les colonnes de type JSON
                    $datesJalon = json_decode($row["dates_jalon"], true);
                    echo "<td>";
                    if (!empty($datesJalon)) {
                        foreach ($datesJalon as $jalon) {
                            echo htmlspecialchars($jalon['date']) . ": " . htmlspecialchars($jalon['text']) . "<br>";
                        }
                    } else {
                        echo "Aucune date jalon";
                    }
                    echo "</td>";
                } else {
                    echo "<td>" . htmlspecialchars($row[$column]) . "</td>";
                }
            }

            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "0 résultats";
    }

    $stmt->close();
    $conn->close();
    ?>

    <script>
        // Script pour afficher/masquer le popup
        document.getElementById('openPopup').addEventListener('click', function() {
            document.getElementById('overlay').classList.add('active');
            document.getElementById('columnPopup').classList.add('active');
        });

        document.getElementById('closePopup').addEventListener('click', function() {
            document.getElementById('overlay').classList.remove('active');
            document.getElementById('columnPopup').classList.remove('active');
        });

        // Script pour sélectionner/désélectionner toutes les lignes
        document.getElementById('selectAll').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.rowCheckbox');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        // Mise à jour des boutons en fonction des lignes sélectionnées
        document.querySelectorAll('.rowCheckbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    document.getElementById('selectAll').checked = false;
                } else if (Array.from(document.querySelectorAll('.rowCheckbox')).every(cb => cb.checked)) {
                    document.getElementById('selectAll').checked = true;
                }
            });
        });
    </script>
</body>
</html>
