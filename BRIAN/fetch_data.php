<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des projets</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Styles pour les dates dépassées */
        .date-depassee {
            color: red;
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
            border-radius: 5px;
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

        /* Styles pour les icônes de tri */
        .sort-icon {
            font-size: 12px;
            margin-left: 5px;
        }
        .sort-asc::after {
            content: '▲';
        }
        .sort-desc::after {
            content: '▼';
        }


        a {
            color: white;
            text-decoration: none; /* Supprimer la sous-ligne */
        }

        /* Pour changer la couleur lors du survol, si nécessaire */
        a:hover {
            color: #ddd; /* Couleur légèrement différente lors du survol */
        }

        /* Styles spécifiques pour les liens de tri dans les en-têtes de tableau */
        th a {
            color: white; /* Couleur blanche pour les liens dans les en-têtes */
        }

        /* Couleur pour les icônes de tri */
        .sort-icon {
            font-size: 12px;
            margin-left: 5px;
            color: white; /* Couleur blanche pour les icônes de tri */
        }
    </style>
    <script>
        function sortTable(column) {
            const url = new URL(window.location.href);
            const currentSort = url.searchParams.get('sort');
            let newSort;

            if (currentSort === column + 'Asc') {
                newSort = column + 'Desc';
            } else {
                newSort = column + 'Asc';
            }

            url.searchParams.set('sort', newSort); // Ajouter ou mettre à jour le paramètre 'sort'
            window.location.href = url.toString(); // Rediriger vers la nouvelle URL
        }
    </script>
</head>
<body>
    <?php
    require_once 'db_connection.php';

    // Inclusion de la feuille de style CSS
    echo "<link rel='stylesheet' type='text/css' href='style.css'>";

    // Récupération des paramètres de la requête GET
    $levier = isset($_GET['levier']) ? $_GET['levier'] : null;
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'dateAsc';
    $showAll = isset($_GET['showAll']) ? filter_var($_GET['showAll'], FILTER_VALIDATE_BOOLEAN) : false;

    // Récupération de l'ID de l'utilisateur connecté
    $userId = $_SESSION['userId'];

    // Déterminez l'ordre de tri basé sur la valeur du paramètre 'sort'
    $sortIcons = [
        'id' => '',
        'intitule' => '',
        'objectifs' => '',
        'date' => '',
        'dateFin' => '',
        'avancement' => '',
        'levier' => '',
        'participants' => ''
    ];

    switch ($sort) {
        case 'idAsc':
            $orderBy = "ID ASC";
            $sortIcons['id'] = 'sort-asc';
            break;
        case 'idDesc':
            $orderBy = "ID DESC";
            $sortIcons['id'] = 'sort-desc';
            break;
        case 'intituleAsc':
            $orderBy = "Intitule ASC";
            $sortIcons['intitule'] = 'sort-asc';
            break;
        case 'intituleDesc':
            $orderBy = "Intitule DESC";
            $sortIcons['intitule'] = 'sort-desc';
            break;
        case 'objectifsAsc':
            $orderBy = "Objectifs ASC";
            $sortIcons['objectifs'] = 'sort-asc';
            break;
        case 'objectifsDesc':
            $orderBy = "Objectifs DESC";
            $sortIcons['objectifs'] = 'sort-desc';
            break;
        case 'dateFinAsc':
            $orderBy = "DateDeFin ASC";
            $sortIcons['dateFin'] = 'sort-asc';
            break;
        case 'dateFinDesc':
            $orderBy = "DateDeFin DESC";
            $sortIcons['dateFin'] = 'sort-desc';
            break;
        case 'avancementAsc':
            $orderBy = "Avancement ASC";
            $sortIcons['avancement'] = 'sort-asc';
            break;
        case 'avancementDesc':
            $orderBy = "Avancement DESC";
            $sortIcons['avancement'] = 'sort-desc';
            break;
        case 'levierAsc':
            $orderBy = "Levier ASC";
            $sortIcons['levier'] = 'sort-asc';
            break;
        case 'levierDesc':
            $orderBy = "Levier DESC";
            $sortIcons['levier'] = 'sort-desc';
            break;
        case 'participantsAsc':
            $orderBy = "Participants ASC";
            $sortIcons['participants'] = 'sort-asc';
            break;
        case 'participantsDesc':
            $orderBy = "Participants DESC";
            $sortIcons['participants'] = 'sort-desc';
            break;
        case 'dateAsc':
            $orderBy = "DateDeDebut ASC";
            $sortIcons['date'] = 'sort-asc';
            break;
        case 'dateDesc':
            $orderBy = "DateDeDebut DESC";
            $sortIcons['date'] = 'sort-desc';
            break;
        default:
            $orderBy = "DateDeDebut ASC";
            break;
    }

    // Construction de la requête SQL en fonction des paramètres
    if ($showAll) {
        $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants FROM projets ORDER BY $orderBy";
    } else {
        $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants 
                  FROM projets 
                  WHERE Participants = ? OR Participants LIKE ? 
                  ORDER BY $orderBy";
    }

    // Ajout de la condition 'Levier' à la requête si le paramètre 'levier' est spécifié
    if ($levier) {
        if ($showAll) {
            $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants 
                      FROM projets 
                      WHERE Levier = ? 
                      ORDER BY $orderBy";
        } else {
            $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants 
                      WHERE Levier = ? AND (Participants = ? OR Participants LIKE ?) 
                      ORDER BY $orderBy";
        }
    }

    // Préparation de la requête SQL avec les paramètres
    $stmt = $conn->prepare($query);
    $searchUserId = "%" . $userId . "%";
    if ($levier) {
        if ($showAll) {
            $stmt->bind_param("s", $levier);
        } else {
            $stmt->bind_param("sss", $levier, $userId, $searchUserId);
        }
    } else {
        if (!$showAll) {
            $stmt->bind_param("ss", $userId, $searchUserId);
        }
    }

    // Exécution de la requête SQL
    $stmt->execute();

    // Récupération des résultats de la requête
    $result = $stmt->get_result();

    // Affichage des résultats dans un tableau HTML
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>
                <label class='custom-checkbox'>
                    <input type='checkbox' id='selectAll'>
                    <span class='checkmark'></span>
                </label>
              </th>"; // Ajout de la case à cocher pour tout sélectionner
        echo "<th><a href='#' onclick=\"sortTable('id')\">ID<span class='sort-icon {$sortIcons['id']}'></span></a></th>";
        echo "<th><a href='#' onclick=\"sortTable('intitule')\">Intitulé<span class='sort-icon {$sortIcons['intitule']}'></span></a></th>";
        echo "<th><a href='#' onclick=\"sortTable('objectifs')\">Objectifs<span class='sort-icon {$sortIcons['objectifs']}'></span></a></th>";
        echo "<th><a href='#' onclick=\"sortTable('date')\">Date de début<span class='sort-icon {$sortIcons['date']}'></span></a></th>";
        echo "<th><a href='#' onclick=\"sortTable('dateFin')\">Date de fin<span class='sort-icon {$sortIcons['dateFin']}'></span></a></th>";
        echo "<th><a href='#' onclick=\"sortTable('avancement')\">Avancement (%)<span class='sort-icon {$sortIcons['avancement']}'></span></a></th>";
        echo "<th><a href='#' onclick=\"sortTable('levier')\">Levier<span class='sort-icon {$sortIcons['levier']}'></span></a></th>";
        echo "<th><a href='#' onclick=\"sortTable('participants')\">Participants<span class='sort-icon {$sortIcons['participants']}'></span></a></th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
            $dateDeFin = new DateTime($row["DateDeFin"]);
            $aujourdhui = new DateTime();
            $classeDateDepassee = ($dateDeFin < $aujourdhui) ? 'date-depassee' : '';

            echo "<tr>";
            echo "<td>
                    <label class='custom-checkbox'>
                        <input type='checkbox' class='rowCheckbox' data-id='" . $row['ID'] . "'>
                        <span class='checkmark'></span>
                    </label>
                  </td>"; // Case à cocher pour chaque ligne
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . htmlspecialchars($row["Intitule"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Objectifs"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["DateDeDebut"]) . "</td>";
            echo "<td class='$classeDateDepassee'>" . htmlspecialchars($row["DateDeFin"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Avancement"]) . "%</td>";
            echo "<td>" . htmlspecialchars($row["Levier"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Participants"]) . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "0 résultats";
    }
    ?>
</body>
</html>
