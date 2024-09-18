<?php
session_start();

// Définir le mot de passe administrateur (vous pouvez le modifier ici)
$adminPassword = 'admin123';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit();
}

// Récupérer l'userId de la session
$userId = $_SESSION['userId'];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredPassword = $_POST['password'];

    // Vérifier si le mot de passe est correct
    if ($enteredPassword === $adminPassword) {
        // Redirection vers la page d'administration
        header('Location: admin_mode.php');
        exit();
    } else {
        $errorMessage = "Mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Authentification Administrateur</title>
    <!-- Inclure la police Noto Sans depuis Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --background-color: #1b1c27;
            --text-color: #FFFFFF;
            --header-background: #252836;
            --button-background: #1F1D2B;
            --button-hover: #252836;
            --form-background: #252836;
            --form-input-background: #252836;
            --form-input-border: #343456;
            --table-background: #1F1D2B;
            --table-text-color: #FFFFFF;
            --table-header-background: #1F1D2B;
            --table-cell-border: #2c2c4600;
            --table-even-row-background: #0f112e;
            --filter-form-button-background: #1F1D2B;
            --filter-form-button-hover: #252836;
        }

        body {
            margin: 0;
            font-family: 'Noto Sans', sans-serif;
            text-align: center;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #252836;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--text-color);
        }

        .login-container .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .login-container label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-color);
            font-size: 20px;
        }

        .login-container input[type="password"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 10px;
            border: none;
            background-color: #323541;
            color: var(--text-color);
            border-radius: 20px;
            box-sizing: border-box;
            transition: background-color 0.3s;
            font-size: 17px;
        }

        .login-container input[type="password"]:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .login-container button {
            width: 100%;
            padding: 15px;
            background-color: #75a8de;
            color: #fff;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 20px;
            transition: background-color 0.3s;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        /* Style pour le message d'erreur */
        .alert {
            color: #f44336;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion Admin</h2>
        <?php if (isset($errorMessage)): ?>
            <div class="alert"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <form method="POST" action="admin_login.php">
            <!-- Suppression du champ Identifiant -->
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
