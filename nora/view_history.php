<?php
session_start();
require_once 'db_connection.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit();
}



?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Modifications</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Historique des Modifications pour le projet ID: <?php echo htmlspecialchars($recordId); ?></h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Champ Modifié</th>
                    <th>Ancienne Valeur</th>
                    <th>Nouvelle Valeur</th>
                    <th>Modifié par</th>
                    <th>Date de Modification</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['field_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['old_value']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['new_value']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['modified_by']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['modified_at']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="table1.php" class="btn btn-primary">Retour</a>
    </div>
</body>
</html>
