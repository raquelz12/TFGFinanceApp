<?php
session_start();
require "..\config\connection.php";

$errors = [];

$name  = trim($_POST["name"]);
$email = trim($_POST["email"]);
$password  = $_POST["password"];

if (empty($name)) {
    $errors[] = "El nombre es obligatorio";
} elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{2,50}$/", $name)) {
    $errors[] = "El nombre solo puede contener letras y espacios";
}

if (empty($email)) {
    $errors[] = "El email es obligatorio";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "El email no es válido";
}

if (empty($password)) {
    $errors[] = "La contraseña es obligatoria";
} elseif (
    strlen($password) < 8 ||
    !preg_match("/[A-Za-z]/", $password) ||
    !preg_match("/[0-9]/", $password)
) {
    $errors[] = "La contraseña debe tener al menos 8 caracteres, una letra y un número";
}

if (empty($errors)) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $errors[] = "Este email ya está registrado";
    }
}

if (!empty($errors)) {
    $_SESSION["register_errors"] = $errors;
    $_SESSION["old_data"] = [
        "name" => $name,
        "email" => $email
    ];
    $_SESSION["message"] = implode(" ", $errors);
    header("Location: ../../register.php");
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, password)
        VALUES (?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $email, $hash]);

$_SESSION["user_id"] = $pdo->lastInsertId();
$_SESSION["user_name"] = $name;

header("Location: ../../profile.php");
exit;
