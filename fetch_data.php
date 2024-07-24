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
    </style>
</head>
<body>
    <?php
    require_once 'db_connection.php';

    // Inclusion de la feuille de style CSS
    echo "<link rel='stylesheet' type='text/css' href='style.css'>";

    // Récupération des paramètres de la requête GET
    $levier = isset($_GET['levier']) ? $_GET['levier'] : null;
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'dateAsc';
    $showAll = isset($_GET['showAll']) ? $_GET['showAll'] : false;

    // Récupération de l'ID de l'utilisateur connecté
    $userId = $_SESSION['userId'];

    // Déterminez l'ordre de tri basé sur la valeur du paramètre 'sort'
    switch ($sort) {
        case 'dateDesc':
            $orderBy = "DateDeDebut DESC";
            break;
        case 'avancementAsc':
            $orderBy = "Avancement ASC";
            break;
        case 'avancementDesc':
            $orderBy = "Avancement DESC";
            break;
        case 'dateAsc':
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
                      FROM projets 
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
        echo "<th>ID</th>";
        echo "<th>Intitulé</th>";
        echo "<th>Objectifs</th>";
        echo "<th>Date de début</th>";
        echo "<th>Date de fin</th>";
        echo "<th>Avancement (%)</th>";
        echo "<th>Levier</th>";
        echo "<th>Participants</th>";
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

    // Fermer la connexion
    $conn->close();
    ?>
</body>
</html>
