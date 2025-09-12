<?php
session_start();
// Dummy credentials for demonstration
$valid_username = 'admin';
$valid_password = 'password123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['user'] = $username;
        header('Location: ../home.php');
        exit();
    } else {
        header('Location: ../login.php?error=Invalid+credentials');
        exit();
    }
} else {
    header('Location: ../login.php');
    exit();
}
