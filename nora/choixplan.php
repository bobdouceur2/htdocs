<?php
// Démarrer une session PHP pour accéder aux variables de session
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant si 'userId' est défini dans la session
if (!isset($_SESSION['userId'])) {
    // Si 'userId' n'est pas défini, rediriger l'utilisateur vers la page de connexion (index.php)
    header('Location: index.php');
    exit(); // Terminer l'exécution du script après la redirection
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Déclaration de l'encodage des caractères en UTF-8 -->
    <meta charset="UTF-8">
    <!-- Titre de la page qui s'affichera dans l'onglet du navigateur -->
    <title>Accueil Gestion de Projets</title>
    
    <!-- Inclusion du fichier CSS externe pour la mise en page -->
    <link rel="stylesheet" href="style.css">

    <!-- Styles CSS directement dans le fichier pour la mise en forme -->
    <style>
        :root {
        /* Définition des variables CSS pour les couleurs principales de la page */
        --background-color: #191A21; /* Couleur de fond principale en bleu nuit très sombre */
        --text-color: #FFFFFF; /* Couleur de texte en blanc */
        --header-background: #252836; /* Couleur de l'arrière-plan de l'en-tête */
        --button-background: #1F1D2B; /* Couleur de fond des boutons */
        --button-hover: #252836; /* Couleur des boutons lors du survol */
        --form-background: #252836; /* Couleur de fond des formulaires */
        --form-input-background: #2C2F3F; /* Couleur de fond des champs de formulaire */
        --form-input-border: #487395; /* Couleur de bordure des champs de formulaire */
    }

    /* Styles globaux pour le corps de la page */
    body {
        background-color: var(--background-color); /* Utilisation de la couleur de fond définie */
        color: var(--text-color); /* Utilisation de la couleur de texte définie */
        font-family: 'Noto Sans', sans-serif; /* Police utilisée dans la page */
        margin: 0; /* Suppression des marges par défaut */
        padding: 0; /* Suppression des espacements internes par défaut */
    }

    /* Positionnement du logo dans le coin supérieur gauche */
    #logoSafran {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1100; /* S'assurer que le logo reste au-dessus des autres éléments */
    }

    /* Style de la modal affichée pour le choix du plan */
    .modal {
        display: block; /* La modal est affichée par défaut */
        position: fixed; /* La modal reste visible lors du défilement de la page */
        z-index: 1000; /* La modal est placée au-dessus des autres éléments */
        left: 0;
        top: 0;
        width: 100%; /* La modal occupe toute la largeur de l'écran */
        height: 100%; /* La modal occupe toute la hauteur de l'écran */
        overflow: auto; /* Activer le défilement si le contenu dépasse la fenêtre */
        background-color: #1b1c27; /* Couleur de fond sombre */
    }

    /* Style du contenu à l'intérieur de la modal */
    .modal-content {
        background: var(--form-background); /* Couleur de fond de la zone centrale de la modal */
        margin: 5% auto; /* Centrage vertical et horizontal de la modal */
        padding: 40px; /* Espacement interne */
        width: 90%; /* La modal occupe 90% de la largeur de la fenêtre */
        max-width: 800px; /* Limite la largeur maximale à 800px */
        text-align: center; /* Centrer le texte */
        border-radius: 20px; /* Bords arrondis */
        position: relative;
        color: var(--text-color); /* Couleur du texte */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Ombre portée pour un effet visuel de profondeur */
    }

    /* Style des titres dans la modal */
    .modal-content h2 {
        font-size: 2rem; /* Taille de police agrandie pour les titres */
        margin-bottom: 20px; /* Espacement sous le titre */
    }

    /* Conteneur pour les logos dans la modal */
    .logo-container {
        display: flex; /* Flexbox pour aligner les logos */
        justify-content: center; /* Centrer les logos horizontalement */
        gap: 40px; /* Espace entre les logos */
        margin-top: 20px; /* Espace au-dessus du conteneur */
    }

    /* Styles pour les logos cliquables */
    .logo {
        cursor: pointer; /* Changement du curseur en "main" lors du survol */
        transition: transform 0.3s ease; /* Animation de zoom au survol */
    }

    /* Effet de zoom sur les logos au survol */
    .logo:hover {
        transform: scale(1.1); /* Agrandir légèrement le logo */
    }

    /* Limiter la taille des logos à une largeur maximale */
    .logo img {
        max-width: 150px;
        height: auto; /* Maintenir le ratio de l'image */
    }

    /* Styles pour les champs de formulaire dans la modal */
    .modal-content input {
        width: 90%; /* Champ de saisie prend 90% de la largeur */
        padding: 15px; /* Espacement interne */
        margin: 20px 0; /* Espacement autour du champ */
        background-color: var(--form-input-background); /* Couleur de fond */
        color: var(--text-color); /* Couleur du texte */
        font-size: 1.1rem; /* Taille du texte */
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2); /* Effet de profondeur */
    }

    /* Styles pour les boutons dans la modal */
    .modal-content button {
        padding: 15px 30px; /* Espacement interne */
        background-color: var(--button-background); /* Couleur de fond des boutons */
        color: var(--text-color); /* Couleur du texte */
        border: none; /* Suppression des bordures */
        border-radius: 20px; /* Bords arrondis pour harmoniser le design */
        cursor: pointer; /* Changement du curseur au survol */
        font-size: 1.2rem; /* Taille de la police du bouton */
        transition: background-color 0.3s; /* Animation au survol */
    }

    /* Changement de la couleur de fond du bouton lors du survol */
    .modal-content button:hover {
        background-color: var(--button-hover);
    }

    /* Bouton de fermeture de la modal */
    .close {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 30px; /* Taille de la croix de fermeture */
        color: #aaa; /* Couleur de la croix de fermeture */
        cursor: pointer; /* Curseur en forme de main */
    }
    </style>

    <!-- Inclusion du fichier JavaScript externe -->
    <script src="functions.js"></script>
</head>
<body>

    <!-- Affichage du logo de Safran en haut à gauche -->
    <img src="logosafran.png" alt="Logo Safran" id="logoSafran" />

    <!-- En-tête principal contenant le logo et les informations utilisateur -->
    <header class="main-header">
        <div class="logo-safran">
            <!-- Logo de Safran affiché dans l'en-tête -->
            <img src="logosafran.png" alt="Logo Safran" class="logo-image">
        </div>
        <div class="user-info">
            <!-- Affichage de l'identifiant utilisateur depuis la session -->
            Identifiant utilisateur: <?php echo htmlspecialchars($_SESSION['userId']); ?>
        </div>
    </header>

    <!-- Modal d'accueil pour choisir le plan de transformation -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h2>Choix du plan de Transformation</h2>

            <br>

            <!-- Conteneur pour afficher deux logos qui redirigent vers différentes pages -->
            <div class="logo-container">
                <div class="logo" onclick="window.location.href='table1.php';">
                    <!-- Premier logo redirigeant vers la page table1.php -->
                    <img src="logo1.png" alt="Logo 1" class="logo-image"/>
                </div>
                <div class="logo" onclick="window.location.href='table2.php';">
                    <!-- Deuxième logo redirigeant vers la page table2.php -->
                    <img src="logo2.png" alt="Logo 2" class="logo-image"/>
                </div>
            </div>
            
        </div>
    </div>

</body>
</html>
