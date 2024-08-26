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
    <link rel="stylesheet" href="choixplan.css">
    <script src="functions.js"></script>
</head>
<body>

    <!-- Conteneur pour l'identifiant utilisateur en haut à droite -->
    <div class="user-info">
        Identifiant utilisateur: <?php echo htmlspecialchars($_SESSION['userId']); ?>
    </div>

    <!-- Titre principal pour le choix du plan de transformation -->
    <div class="header-wrapper">
        <div class="header-text">Choix du plan de transformation</div>
    </div>


    <!-- Conteneur pour les logos -->
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

    <!-- Autres éléments de votre page d'accueil -->
</body>
</html>
