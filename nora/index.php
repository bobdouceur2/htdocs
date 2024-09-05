<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande d'Identifiants</title>
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
</head>
<body>

    <img src="logosafran.png" alt="Logo Safran" id="logoSafran" />

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h2>Veuillez entrer votre identifiant</h2>
            <input type="text" id="userId" placeholder="Entrez votre identifiant" value="brian.collo@safrangroup.com">  
            <button id="validateButton">Valider</button>
        </div>
    </div>

    <script>
        function validateUserId() {
            const userId = document.getElementById('userId').value;
            console.log('User ID:', userId);  // Debug log
            if (userId) {
                // Stocker l'identifiant dans la session via une requête POST
                fetch('store_user_id.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'userId=' + encodeURIComponent(userId)
                })
                .then(response => {
                    if (response.ok) {
                        // Rediriger vers choixplan.php après le stockage
                        window.location.href = 'choixplan.php';
                    } else {
                        alert('Erreur lors de la sauvegarde de l\'identifiant.');
                    }
                })
                .catch(error => console.error('Erreur:', error));
            } else {
                alert('Veuillez entrer un identifiant.');
            }
        }

        // Associer la fonction validateUserId au bouton Valider
        document.getElementById('validateButton').addEventListener('click', validateUserId);

        // Ajouter un écouteur d'événements pour la touche "Entrée" sur l'élément input
        document.getElementById('userId').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                validateUserId();
            }
        });
    </script>
</body>
</html>
