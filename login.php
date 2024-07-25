<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style2.css"> <!-- Assurez-vous que le chemin est correct -->
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <!--
        <div class="wrapper">
            <svg>
                <text x="80" y="50%" text-anchor="start" dominant-baseline="middle">SE</text>
            </svg>
        </div>
-->
    </header>
    
    <div class="sidebar">
        <img src="safran_logo.png" alt="Safran Logo" class="logo">
        <div class="icon" id="home-icon">
            <img src="home.png" alt="Home Icon">
        </div>
        <div class="icon">
            <img src="dashboard.png" alt="Dashboard Icon">
        </div>
        <div class="icon active">
            <div class="outer-rectangle">
                <div class="inner-rectangle"></div>
            </div>
            <img src="settingsb.png" alt="Settings Icon" class="settings-icon">
        </div>
        <div class="icon bottom-icon">
            <img src="disconnect.png" alt="Disconnect Icon">
        </div>
    </div>
    
    <div class="main-container">
        <div class="card card-center">
            <div class="formContainer">
                <h2>Login</h2>
                <form action="login_process.php" method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit" class="button">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Ajoutez l'événement de clic pour rediriger vers table1.php
        document.getElementById('home-icon').addEventListener('click', function() {
            window.location.href = 'table1.php';
        });
    </script>
</body>
</html>
