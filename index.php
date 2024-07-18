<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande d'Identifiants</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styles pour la modal d'identification */
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
            background: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 10px;
            position: relative;
        }

        .modal-content input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-content button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #45a049;
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

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h2>Veuillez entrer votre identifiant</h2>
            <input type="text" id="userId" placeholder="Entrez votre identifiant">
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
