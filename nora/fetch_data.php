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

// Construire la requête SQL dynamique avec tri par warning_level
$columns_to_select = implode(", ", array_map(function($col) {
    return "`" . $col . "`";  // Échapper chaque colonne avec des backticks pour éviter les erreurs
}, $selected_columns));

$query = "
    SELECT $columns_to_select, ID 
    FROM projets";

// Ajouter une condition pour n'afficher que les projets liés à l'utilisateur si "showAll" n'est pas activé
if (!$showAll) {
    $query .= " WHERE LOWER(Equipe) LIKE LOWER(?)";
}

// Ajouter l'ordre par warning_level après la condition WHERE
$query .= " ORDER BY 
    CASE
        WHEN warning_level = 'rouge' THEN 1
        WHEN warning_level = 'orange' THEN 2
        WHEN warning_level = 'vert' THEN 3
        ELSE 4
    END, ID";

$stmt = $conn->prepare($query);

// Lier l'ID de l'utilisateur si le filtre est actif
if (!$showAll) {
    $userIdLike = '%' . $userId . '%';
    $stmt->bind_param("s", $userIdLike);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1' id='projectsTable'>";
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
        echo "<tr data-id='" . htmlspecialchars($row['ID'] ?? '', ENT_QUOTES, 'UTF-8') . "'>";
        echo "<td>
                <label class='custom-checkbox'>
                    <input type='checkbox' class='rowCheckbox'>
                    <span class='checkmark'></span>
                </label>
              </td>";

        foreach ($selected_columns as $column) {
            if ($column == "dates_jalon") {
                if (!empty($row["dates_jalon"])) {
                    $datesJalon = json_decode($row["dates_jalon"], true);
                } else {
                    $datesJalon = [];
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
            } elseif ($column == "warning_level") {
                $warning_level = htmlspecialchars($row["warning_level"] ?? '', ENT_QUOTES, 'UTF-8');
                if ($warning_level == "vert") {
                    echo "<td><span class='status-circle green'></span> Vert</td>";
                } elseif ($warning_level == "orange") {
                    echo "<td><span class='status-circle orange'></span> Orange</td>";
                } elseif ($warning_level == "rouge") {
                    echo "<td><span class='status-circle red'></span> Rouge</td>";
                } else {
                    echo "<td>Aucun niveau</td>";
                }
            } else {
                echo "<td contenteditable='true' class='editable-cell' data-column='" . htmlspecialchars($column, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row[$column] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
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
        /* Styles divers */
        .editable-cell {
            background-color: #1F1D2B;
            cursor: pointer;
        }

        .editable-cell:focus {
            outline: none;
            background-color: #1b1c27;
        }

        .status-circle {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .green {
            background-color: green;
        }

        .orange {
            background-color: orange;
        }

        .red {
            background-color: red;
        }
    </style>
</head>
<body>

<script>
document.querySelectorAll('.editable-cell').forEach(cell => {
    cell.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const row = this.closest('tr');
            const projectId = row.getAttribute('data-id');
            const columnName = this.getAttribute('data-column');
            const newValue = this.innerText;

            // Envoyer la mise à jour via AJAX
            updateProject(projectId, columnName, newValue);
        }
    });
});

function updateProject(id, column, value) {
    fetch('update_projectb.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id, column: column, value: value }),
    })
    .then(response => response.text())
    .then(result => {
        alert('Mise à jour réussie');
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}
</script>
</body>
</html>
