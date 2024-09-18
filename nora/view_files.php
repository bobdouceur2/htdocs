<?php
require_once 'db_connection.php'; // Inclure votre fichier de connexion à la base de données

// Vérifier si une requête de suppression est reçue
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    // Préparer la requête de suppression
    $stmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        echo "Le fichier a été supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du fichier : " . $stmt->error;
    }

    $stmt->close();
}

// Gérer la recherche de fichiers
$searchTerm = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = "SELECT d.id, d.name, d.note, p.Intitule AS projet_name 
              FROM documents d 
              LEFT JOIN projets p ON d.projet_id = p.id 
              WHERE d.name LIKE ? OR d.note LIKE ? OR p.Intitule LIKE ?";
    $stmt = $conn->prepare($query);
    $likeTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("sss", $likeTerm, $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Récupérer tous les fichiers de la base de données avec leur projet associé
    $query = "SELECT d.id, d.name, d.note, p.Intitule AS projet_name 
              FROM documents d 
              LEFT JOIN projets p ON d.projet_id = p.id";
    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voir les Fichiers</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="view_files.css">
</head>
<body>
    <div class="container">
        <h2>Fichiers disponibles</h2>
            
        <!-- Formulaire de recherche -->
        <form method="GET" action="view_files.php" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher un fichier..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Rechercher</button>
                </div>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du fichier</th>
                    <th>Note</th>
                    <th>Projet associé</th>
                    <th>Action</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['note']) . "</td>";
                        echo "<td>" . (!empty($row['projet_name']) ? htmlspecialchars($row['projet_name']) : "Aucun projet") . "</td>";
                        echo "<td><a href='download.php?id=" . urlencode($row['id']) . "' class='btn btn-primary'>Télécharger</a></td>";
                        echo "<td><a href='view_files.php?delete_id=" . urlencode($row['id']) . "' class='btn btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce fichier?\")'>✖</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Aucun fichier disponible</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
