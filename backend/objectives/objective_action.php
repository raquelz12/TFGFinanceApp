<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['action'])) {
    header("Location: ../../objectives.php");
    exit;
}

$action = $_POST['action'];

try {
    if ($action === 'insert') {

        $name = trim($_POST['name']);
        $targetAmount = (float) $_POST['targetAmount'];
        $amount = (float) $_POST['amount'];
        $objective_date = $_POST['objective_date'];

        if (empty($name)) {
            $_SESSION['message'] = "El nombre es obligatorio";
            header("Location: ../../objectives.php");
            exit;
        }

        if ($targetAmount <= 0 || $amount < 0) {
            $_SESSION['message'] = "Cantidades inválidas";
            header("Location: ../../objectives.php");
            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO objectives (user_id, name, amount, completed_amount, objective_date)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $user_id,
            $name,
            $targetAmount,
            $amount,
            $objective_date
        ]);

        $_SESSION['message'] = "Objetivo creado correctamente";
    }

    if ($action === 'update') {

        $id = (int) $_POST['id'];
        $amount = (float) $_POST['amount'];

        if ($amount <= 0) {
            $_SESSION['message'] = "Cantidad inválida";
            header("Location: ../../objectives.php");
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE objectives
            SET amount = ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$amount, $id, $user_id]);

        $_SESSION['message'] = "Objetivo actualizado correctamente";
    }

    if ($action === 'add_money') {

        $id = (int) $_POST['id'];
        $amount = (float) $_POST['amount'];

        if ($amount <= 0) {
            $_SESSION['message'] = "Cantidad inválida";
            header("Location: ../../objectives.php");
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE objectives
            SET completed_amount = completed_amount + ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$amount, $id, $user_id]);

        $_SESSION['message'] = "Dinero añadido al objetivo";
    }

    if ($action === 'delete') {

        $id = (int) $_POST['id'];

        $stmt = $pdo->prepare("
            DELETE FROM objectives
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$id, $user_id]);

        $_SESSION['message'] = "Objetivo eliminado correctamente";
    }

} catch (PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
}

header("Location: ../../objectives.php");
exit;
