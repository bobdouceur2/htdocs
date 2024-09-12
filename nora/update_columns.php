<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inclure la connexion à la base de données
    require_once 'db_connection.php';

    // Récupérer les colonnes soumises via le formulaire
    $columns = isset($_POST['columns']) ? $_POST['columns'] : [];

    // Valider que c'est un tableau et qu'il n'est pas vide
    if (!is_array($columns) || empty($columns)) {
        echo "Erreur: aucune colonne sélectionnée.";
        exit();
    }

    // Supprimer toutes les colonnes actuelles dans la table default_columns
    $delete_query = "DELETE FROM default_columns";
    if (!$conn->query($delete_query)) {
        echo "Erreur lors de la suppression des colonnes existantes: " . $conn->error;
        exit();
    }

    // Préparer la requête pour insérer les nouvelles colonnes
    $insert_query = $conn->prepare("INSERT INTO default_columns (column_name) VALUES (?)");

    // Insérer chaque colonne sélectionnée
    foreach ($columns as $column) {
        $insert_query->bind_param('s', $column);
        if (!$insert_query->execute()) {
            echo "Erreur lors de l'insertion de la colonne: " . $conn->error;
            exit();
        }
    }

    // Confirmer la mise à jour des colonnes par défaut
    echo "Les colonnes par défaut ont été mises à jour avec succès.";
}
?>
