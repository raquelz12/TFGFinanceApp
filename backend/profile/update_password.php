<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$errors = [];

$current = $_POST["current_password"] ?? "";
$new     = $_POST["new_password"] ?? "";
$confirm = $_POST["confirm_new_password"] ?? "";

$stmt = $pdo->prepare(
    "SELECT password FROM users WHERE id = ?"
);
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch();

if (!$user || !password_verify($current, $user["password"])) {
    $errors[] = "La contraseña actual no es correcta";
}

if (
    strlen($new) < 8 ||
    !preg_match("/[A-Za-z]/", $new) ||
    !preg_match("/[0-9]/", $new)
) {
    $errors[] = "La nueva contraseña debe tener al menos 8 caracteres, una letra y un número";
}

if ($new !== $confirm) {
    $errors[] = "Las contraseñas no coinciden";
}

if ($errors) {
    $_SESSION["message"] = $errors;
    header("Location: ../../profile.php");
    exit;
}

$hash = password_hash($new, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "UPDATE users SET password = ? WHERE id = ?"
);
$stmt->execute([$hash, $_SESSION["user_id"]]);

$_SESSION["message"] = ["Contraseña actualizada correctamente"];
header("Location: ../../profile.php");
exit;