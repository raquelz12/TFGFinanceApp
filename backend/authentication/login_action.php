<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$email = $_POST["email"];
$password  = $_POST["password"];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($email === "" || $password === "") {
    $_SESSION["message"] = "Por favor, completa todos los campos.";
    header("Location: ../../login.php");
    exit;
}

if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_name"] = $user["name"];

    header("Location: ../../app_dashboard.php");
    exit;
}

$_SESSION["message"] = "Credenciales inválidas. Por favor, inténtalo de nuevo.";
header("Location: ../../login.php");
exit;
