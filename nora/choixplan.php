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
    
    <link rel="stylesheet" href="style.css">

    <style>
        :root {
        --background-color: #191A21; /* Couleur de fond principale en bleu nuit très sombre */
        --text-color: #FFFFFF; /* Couleur de texte en blanc */
        --header-background: #252836; /* Couleur du header */
        --button-background: #1F1D2B; /* Couleur des boutons */
        --button-hover: #252836; /* Couleur différente des boutons lors du survol */
        --form-background: #252836; /* Couleur de fond des formulaires */
        --form-input-background: #2C2F3F; /* Fond légèrement différent pour les champs de formulaire */
        --form-input-border: #487395; /* Bordure des champs de formulaire */
    }

    /* Styles pour la modal d'identification */
    body {
        background-color: var(--background-color);
        color: var(--text-color);
        font-family: 'Noto Sans', sans-serif;
        margin: 0;
        padding: 0;
    }

    #logoSafran {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1100;
    }

    .modal {
        display: block; /* Affichez la modal par défaut */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: #1b1c27;
    }

    .modal-content {
        background: var(--form-background);
        margin: 5% auto;
        padding: 40px;
        width: 90%;
        max-width: 800px;
        text-align: center;
        border-radius: 20px; /* Alignement avec les autres éléments de design */
        position: relative;
        color: var(--text-color);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Ajout d'une ombre portée */
    }

    .modal-content h2 {
        font-size: 2rem;
        margin-bottom: 20px;
    }

    .logo-container {
        display: flex; /* Utiliser flexbox pour aligner les logos côte à côte */
        justify-content: center; /* Centre les logos horizontalement */
        gap: 40px; /* Espace entre les logos */
        margin-top: 20px; /* Espace en haut */
    }

    .logo {
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .logo:hover {
        transform: scale(1.1); /* Zoomer légèrement au survol */
    }

    .logo img {
        max-width: 150px; /* Limite la taille des logos */
        height: auto;
    }

    .modal-content input {
        width: 90%;
        padding: 15px;
        margin: 20px 0;
        background-color: var(--form-input-background); /* Nouvelle couleur de fond pour l'input */
        color: var(--text-color);
        font-size: 1.1rem;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2); /* Effet de profondeur pour les champs de formulaire */
    }

    .modal-content button {
        padding: 15px 30px;
        background-color: var(--button-background);
        color: var(--text-color);
        border: none;
        border-radius: 20px; /* Alignement avec les autres éléments de design */
        cursor: pointer;
        font-size: 1.2rem;
        transition: background-color 0.3s;
    }

    .modal-content button:hover {
        background-color: var(--button-hover);
    }

    .close {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 30px;
        color: #aaa;
        cursor: pointer;
    }

    </style>
    <script src="functions.js"></script>
</head>
<body>

    <img src="logosafran.png" alt="Logo Safran" id="logoSafran" />

    <!-- Header combinant le logo Safran et l'identifiant utilisateur -->
    <header class="main-header">
        <div class="logo-safran">
            <img src="logosafran.png" alt="Logo Safran" class="logo-image">
        </div>
        <div class="user-info">
            Identifiant utilisateur: <?php echo htmlspecialchars($_SESSION['userId']); ?>
        </div>
    </header>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h2>Choix du plan de Transformation</h2>

            <br>

            <!-- Conteneur pour les logos -->
            <div class="logo-container">
                <div class="logo" onclick="window.location.href='table1.php';">
                    <img src="logo1.png" alt="Logo 1" class="logo-image"/>
                </div>
                <div class="logo" onclick="window.location.href='table2.php';">
                    <img src="logo2.png" alt="Logo 2" class="logo-image"/>
                </div>
            </div>
            
        </div>
    </div>

</body>
</html>
