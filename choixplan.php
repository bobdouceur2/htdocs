<?php
session_start();
if (!isset($_SESSION['userId'])) {
    // Rediriger vers index.php si l'identifiant n'est pas défini
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Gestion de Projets</title>
    <link rel="stylesheet" href="home.css">
    <script src="functions.js"></script>
</head>
<body>

    <div class="header-text">Choix du plan de transformation</div>
    <div class="logos-container">
        <!-- Logo 1 -->
        <div class="logo" onclick="window.location.href='table1.php';">
            <img src="logo1.png" alt="Logo 1" class="logo-image"/>
        </div>
        <!-- Logo 2 -->
        <div class="logo" onclick="window.location.href='table2.php';">
            <img src="logo2.png" alt="Logo 2" class="logo-image"/>
        </div>
    </div>
    <!-- Afficher l'identifiant utilisateur pour confirmation (optionnel) -->
    <div class="user-info">
        Identifiant utilisateur: <?php echo htmlspecialchars($_SESSION['userId']); ?>
    </div>
    <!-- Autres éléments de votre page d'accueil -->
</body>
</html>
