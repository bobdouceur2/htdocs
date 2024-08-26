<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande d'Identifiants</title>
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --background-color: #0a0f29; /* Couleur de fond principale en bleu nuit très sombre */
            --text-color: #ffffff; /* Couleur de texte en gris clair */
            --header-background: #121a45; /* Couleur du header en bleu foncé */
            --button-background: #1a245b; /* Couleur des boutons en bleu très sombre */
            --button-hover: #2d3c7d; /* Couleur différente des boutons lors du survol */
            --form-background: #121a45; /* Couleur de fond des formulaires en bleu foncé */
            --form-input-background: #1c1c2e; /* Fond des champs de formulaire en bleu très foncé */
            --form-input-border: #343456; /* Bordure des champs de formulaire en gris foncé */
        }

        /* Styles pour la modal d'identification */
        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: Arial, sans-serif;
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
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: var(--form-background);
            margin: 5% auto;
            padding: 40px;
            border: 1px solid var(--form-input-border);
            width: 90%;
            max-width: 800px;
            text-align: center;
            border-radius: 10px;
            position: relative;
            color: var(--text-color);
        }

        .modal-content h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .modal-content input {
            width: 90%;
            padding: 15px;
            margin: 20px 0;
            background-color: var(--form-input-background);
            border: 1px solid var(--form-input-border);
            border-radius: 5px;
            color: var(--text-color);
            font-size: 1.1rem;
        }

        .modal-content button {
            padding: 15px 30px;
            background-color: #45a049; /* Ne pas modifier cette couleur */
            color: var(--text-color);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
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
