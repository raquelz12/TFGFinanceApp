<?php
session_start();
require "..\config\connection.php";

$errors = [];
$name = trim($_POST["name"] ?? "");

if (empty($name)) {
    $errors[] = "El nombre es obligatorio";
} elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{2,50}$/", $name)) {
    $errors[] = "El nombre solo puede contener letras y espacios";
}

if ($errors) {
    $_SESSION["message"] = $errors;
    header("Location: ../../profile.php");
    exit;
}

$stmt = $pdo->prepare(
    "UPDATE users SET name = ? WHERE id = ?"
);
$stmt->execute([$name, $_SESSION["user_id"]]);

$_SESSION["user_name"] = $name;

$_SESSION["message"] = ["Perfil actualizado correctamente"];
header("Location: ../../profile.php");
exit;
