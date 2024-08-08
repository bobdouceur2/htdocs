<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userId'])) {
    $_SESSION['userId'] = $_POST['userId'];
    http_response_code(200);
} else {
    http_response_code(400);
}

