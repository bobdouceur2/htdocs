<?php
// Commencer la session
session_start();

$admins = [
    'admin1' => 'password123',
    'admin2' => 'password456',
];

// Vérifier les identifiants envoyés depuis le formulaire de connexion
if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Si les identifiants correspondent, rediriger vers la page d'administration
    if (isset($admins[$username]) && $admins[$username] == $password) {
        $_SESSION['user_logged_in'] = $username; // Mettre en place une variable de session
        header('Location: admin_table_1.php');
        exit;
    } else {
        // Sinon, renvoyer l'utilisateur vers la page de connexion avec un message d'erreur
        header('Location: admin_login.php?error=1');
        exit;
    }
}
?>
