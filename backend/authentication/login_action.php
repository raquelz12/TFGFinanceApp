<?php
session_start();
require "..\config\connection.php";

$email = $_POST["email"];
$password  = $_POST["password"];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_name"] = $user["name"];

    header("Location: ../../app_dashboard.php");
    exit;
}

$_SESSION["message"] = "Credenciales inválidas. Por favor, inténtalo de nuevo.";
header("Location: ../../login.php");
exit;
