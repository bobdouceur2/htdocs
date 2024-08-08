<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projets";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Note: Pas besoin de fermer la connexion ici, elle sera fermée à la fin des scripts qui l'utilisent.
