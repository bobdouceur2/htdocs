<?php
// Affichage des erreurs sur le navigateur pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php';

// Vérifier si l'utilisateur veut afficher tous les projets ou seulement ceux où il est dans l'équipe
$showAll = isset($_GET['showAll']) ? true : false;

// Récupérer les colonnes par défaut depuis la base de données
$default_columns_query = "SELECT column_name FROM default_columns";
$default_columns_result = $conn->query($default_columns_query);

$default_columns = [];
if ($default_columns_result && $default_columns_result->num_rows > 0) {
    while ($row = $default_columns_result->fetch_assoc()) {
        $default_columns[] = $row['column_name'];
    }
} else {
    // Si aucune colonne par défaut n'est trouvée, définir une liste par défaut dans le code
    $default_columns = ['Intitule', 'DescriptionProbleme', 'ObjectifsOperationnels', 'DateDeDebut', 'DateDeFin', 'dates_jalon', 'Avancement', 'Equipe'];
}
// Vérifier quelles colonnes sont sélectionnées dans le formulaire
$selected_columns = isset($_POST['columns']) ? $_POST['columns'] : $default_columns;

// Construire la requête SQL dynamique
$columns_to_select = implode(", ", $selected_columns);
$query = "SELECT $columns_to_select, ID FROM projets";

// Ajouter une condition pour n'afficher que les projets liés à l'utilisateur si "showAll" n'est pas activé
if (!$showAll) {
    // Utiliser LOWER() pour rendre la recherche insensible à la casse et chercher l'e-mail de l'utilisateur
    $query .= " WHERE LOWER(Equipe) LIKE LOWER(?)";
}

$stmt = $conn->prepare($query);

// Lier l'ID de l'utilisateur si le filtre est actif
if (!$showAll) {
    // Rechercher l'e-mail de l'utilisateur sans se soucier des caractères environnants
    // % correspond à n'importe quel caractère avant ou après l'e-mail dans la chaîne
    $userIdLike = '%' . $userId . '%';
    $stmt->bind_param("s", $userIdLike);
}

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
                    <input type='checkbox' class='rowCheckbox' data-id='" . htmlspecialchars($row['ID'] ?? '', ENT_QUOTES, 'UTF-8') . "'>
                    <span class='checkmark'></span>
                </label>
              </td>";

        // Affichage dynamique des données des colonnes
        foreach ($selected_columns as $column) {
            if ($column == "dates_jalon") {
                // Vérification si "dates_jalon" n'est pas vide ou null avant d'utiliser json_decode
                if (!empty($row["dates_jalon"])) {
                    $datesJalon = json_decode($row["dates_jalon"], true);
                } else {
                    $datesJalon = []; // Valeur par défaut si dates_jalon est null ou vide
                }

                echo "<td>";
                if (!empty($datesJalon)) {
                    foreach ($datesJalon as $jalon) {
                        echo htmlspecialchars($jalon['date'] ?? '', ENT_QUOTES, 'UTF-8') . ": " . htmlspecialchars($jalon['text'] ?? '', ENT_QUOTES, 'UTF-8') . "<br>";
                    }
                } else {
                    echo "Aucune date jalon";
                }
                echo "</td>";
            } else {
                echo "<td>" . htmlspecialchars($row[$column] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
            }
        }

        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "0 résultats";
}
?>












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

        .date-depassee {
            color: red;
        }

        .hidden-column {
            display: none;
        }

        /* Cacher la case à cocher par défaut */
        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
            justify-content: center; /* Centrer les checkboxes horizontalement */
        }


        .custom-checkbox .checkmark {
            position: relative;
            width: 25px;
            height: 25px;
            background-color: #eee;
            border-radius: 4px;
            margin-right: 10px;
        }

        /* Couleur de fond lorsque la case est cochée */
        .custom-checkbox input:checked + .checkmark {
            background-color: #2196F3;
        }

        /* Ajouter le symbole de validation (✓) */
        .custom-checkbox .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Afficher le symbole lorsque la case est cochée */
        .custom-checkbox input:checked + .checkmark:after {
            display: block;
        }

        /* Style du symbole (✓) */
        .custom-checkbox .checkmark:after {
            left: 9px;
            top: 7px;
            width: 8px;
            height: 14px;
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
            width: 75px;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: var(--form-background);
            padding: 20px;
            border: 2px solid var(--form-input-border);
            border-radius: 8px;
            z-index: 1000;
            width: 90%; /* Augmentation de la largeur */
            max-width: 1000px; /* Limite de la largeur maximale */
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .popup.active {
            display: block;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }

        .overlay.active {
            display: block;
        }

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

        .popup .close-icon {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
        }

        .reset-icon {
            position: absolute;
            top: 15px;
            left: 20px;
            cursor: pointer;
            color: #fff;
            font-size: 30px;
            font-weight: bold;
        }

        .close-icon {
            position: absolute!important;
            top: 0px!important;
            right: 20px!important;
            cursor: pointer!important;
            color: #fff!important;
            font-size: 50px!important;
            font-weight: bold!important;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        button[type="submit"]:active {
            background-color: #397d3a;
            box-shadow: 0 5px #666;
            transform: translateY(2px);
        }

        .popup *:not(i) {
            font-size: calc(100% + 2.5px);
        }

        .columns-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Deux colonnes */
            gap: 40px; /* Espacement entre les colonnes */
            justify-content: center; /* Centrer les colonnes horizontalement */
            text-align: center;
        }

        .custom-h3 {
            font-size: 30px!important; /* Taille de police agrandie */
            font-weight: bold; /* Texte en gras */
            color: #ffffff; /* Couleur blanche, ajustable selon votre thème */
            margin-bottom: 20px; /* Espace sous le titre */
            letter-spacing: 1px; /* Espacement entre les lettres */
            
        }
















    </style>
</head>
<body>
    <div class="icon-card">
        <button id="openPopup" class="icon-button">
            <i class="fas fa-cog"></i>
        </button>
    </div>

    <div id="overlay" class="overlay"></div>

    <div id="columnPopup" class="popup">
        <span class="reset-icon" id="resetColumns"><i class="fas fa-sync-alt"></i></span>
        <span class="close-icon" id="closePopup">&times;</span>
        <br><br>
        <form method="POST" id="columnSelector">
            <h3 class="custom-h3">Choisissez les colonnes à afficher :</h3>

            <br><br>
            <?php
                // Requête pour récupérer les colonnes de la table "projets"
                $query = "SHOW COLUMNS FROM projets";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    echo "<div class='columns-wrapper'>"; // Début du conteneur pour les colonnes

                    while ($row = $result->fetch_assoc()) {
                        $column_name = $row['Field'];
                        // Cocher la colonne si elle fait partie des colonnes sélectionnées
                        $checked = in_array($column_name, $selected_columns) ? 'checked' : '';

                        // Afficher les checkboxes avec la classe 'custom-checkbox' et 'checkmark'
                        echo "<label class='custom-checkbox'><input type='checkbox' name='columns[]' value='$column_name' $checked><span class='checkmark'></span> $column_name</label>";
                    }

                    echo "</div>"; // Fin du conteneur pour les colonnes
                } else {
                    echo 'Aucune colonne trouvée.';
                }

                $result->free();
            ?>

            <br><br>
            <button type="submit">Mettre à jour le tableau</button>
        </form>
        
    </div>

    <script>
        document.getElementById('openPopup').addEventListener('click', function() {
            document.getElementById('overlay').classList.add('active');
            document.getElementById('columnPopup').classList.add('active');
        });

        document.getElementById('closePopup').addEventListener('click', function() {
            document.getElementById('overlay').classList.remove('active');
            document.getElementById('columnPopup').classList.remove('active');
        });

        document.getElementById('selectAll').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.rowCheckbox');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        document.querySelectorAll('.rowCheckbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    document.getElementById('selectAll').checked = false;
                } else if (Array.from(document.querySelectorAll('.rowCheckbox')).every(cb => cb.checked)) {
                    document.getElementById('selectAll').checked = true;
                }
            });
        });

        // Réinitialiser les colonnes à afficher aux colonnes par défaut
        document.getElementById('resetColumns').addEventListener('click', function() {
            document.querySelectorAll('input[name="columns[]"]').forEach(checkbox => {
                checkbox.checked = ['Intitule', 'DescriptionProbleme', 'ObjectifsOperationnels', 'DateDeDebut', 'DateDeFin', 'dates_jalon', 'Avancement', 'Equipe'].includes(checkbox.value);
            });
        });
    </script>
</body>
</html>
