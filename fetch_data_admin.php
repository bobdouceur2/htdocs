<?php
require_once 'db_connection.php';

// Vérifier si un levier a été spécifié dans la requête
$levier = isset($_GET['levier']) ? $_GET['levier'] : null;

// Préparer la requête SQL en fonction de la présence ou non d'un filtre levier
if ($levier) {
    // Utiliser des requêtes préparées pour éviter les injections SQL
    $stmt = $conn->prepare("SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, triangle_pcd, informations_supplémentaires, etapes, transverse, di, dt, sfs, dc, autre, chantier, stream, type_de_gain, source_de_financement, kpi, planification, priorite, axe_pf_se, contact, Levier FROM projets WHERE Levier = ? ORDER BY ID DESC");
    $stmt->bind_param("s", $levier);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT ID, Intitule, Objectifs, DateDeDebut, DateDeFin, Avancement, triangle_pcd, informations_supplémentaires, etapes, transverse, di, dt, sfs, dc, autre, chantier, stream, type_de_gain, source_de_financement, kpi, planification, priorite, axe_pf_se, contact, Levier FROM projets ORDER BY ID DESC");
}

// Vérifier si des lignes ont été retournées
if ($result->num_rows > 0) {
    // Début du tableau
    echo "<table border='1'>";
    // En-têtes du tableau
    echo "<tr><th>ID</th><th>Intitulé</th><th>Objectifs</th><th>Date de début</th><th>Date de fin</th><th>Avancement</th><th>Triangle PCD</th><th>Informations supplémentaires</th><th>Etapes</th><th>Transverse</th><th>DI</th><th>DT</th><th>SFS</th><th>DC</th><th>Autre</th><th>Chantier</th><th>Stream</th><th>Type de gain</th><th>Source de financement</th><th>KPI</th><th>Planification</th><th>Priorité</th><th>Axe PF SE</th><th>Contact</th><th>Levier</th></tr>";

    // Récupération et affichage de chaque ligne de la table
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ID"] . "</td>";
        echo "<td>" . htmlspecialchars($row["Intitule"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Objectifs"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["DateDeDebut"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["DateDeFin"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Avancement"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Levier"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["triangle_pcd"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["informations_supplémentaires"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["etapes"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["di"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["dt"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["sfs"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["dc"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["autre"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["chantier"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["stream"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["type_de_gain"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["source_de_financement"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["kpi"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["planification"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["priorite"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["axe_pf_se"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["contact"]) . "</td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 résultats";
}

// Fermer la connexion à la base de données si vous avez terminé d'utiliser la connexion dans ce script;
?>
