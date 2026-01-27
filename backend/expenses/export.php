<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        e.name AS expense,
        e.amount,
        c.name AS category,
        e.created_at
    FROM expenses e
    JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ?
    ORDER BY e.created_at DESC
");
$stmt->execute([$user_id]);

$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="gastos.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Gasto', 'Importe', 'Categoría', 'Fecha']);

foreach ($expenses as $expense) {
    fputcsv($output, $expense);
}

fclose($output);
exit;
