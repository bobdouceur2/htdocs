<?php
// Activer le rapport d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connection.php';

// Vérifier la connexion à la base de données
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

$project = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    

    // Préparez et exécutez la requête SQL pour récupérer les données du projet
    $query = "SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, Levier, Participants FROM projets WHERE ID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifiez si des données ont été trouvées
    if ($result->num_rows > 0) {
        // Récupérez les données du projet
        $project = $result->fetch_assoc();
        
    } else {
        echo "Aucun projet trouvé avec cet ID.<br>";
    }
} else {
    echo "Aucun ID spécifié.<br>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisation du projet</title>
    <link rel="stylesheet" type="text/css" href="visualisation.css">
</head>
<body>
    <h1>Détails pour l'ID: <?php echo htmlspecialchars($id ?? ''); ?></h1>
    <?php if ($project): ?>
        <div class="dashboard">
            <div class="card large">
                <h2>ID</h2>
                <p><?php echo htmlspecialchars($project['ID'] ?? ''); ?></p>
            </div>
            <div class="card small">
                <h2>Intitulé</h2>
                <p><?php echo htmlspecialchars($project['Intitule'] ?? ''); ?></p>
            </div>
            <div class="card large">
                <h2>Objectifs</h2>
                <p><?php echo htmlspecialchars($project['Objectifs'] ?? ''); ?></p>
            </div>
            <div class="card small">
                <h2>Date de début</h2>
                <p><?php echo htmlspecialchars($project['DateDeDebut'] ?? ''); ?></p>
            </div>
            <?php
                $dateDeFin = new DateTime($project['DateDeFin'] ?? 'now');
                $aujourdhui = new DateTime();
                $classeDateDepassee = ($dateDeFin < $aujourdhui) ? 'date-depassee' : '';
            ?>
            <div class="card small <?php echo $classeDateDepassee; ?>">
                <h2>Date de fin</h2>
                <p><?php echo htmlspecialchars($project['DateDeFin'] ?? ''); ?></p>
            </div>
            <div class="card large">
                <h2>Avancement (%)</h2>
                <p><?php echo htmlspecialchars($project['Avancement'] ?? ''); ?>%</p>
            </div>
            <div class="card small">
                <h2>Levier</h2>
                <p><?php echo htmlspecialchars($project['Levier'] ?? ''); ?></p>
            </div>
            <div class="card small">
                <h2>Participants</h2>
                <p><?php echo htmlspecialchars($project['Participants'] ?? ''); ?></p>
            </div>
        </div>
    <?php else: ?>
        <p>Aucune donnée de projet à afficher.</p>
    <?php endif; ?>
</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>
