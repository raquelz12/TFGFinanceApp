<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    exit;
}

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$user_id = $_SESSION['user_id'];

if (!isset($_GET['year'], $_GET['month'])) {
    exit;
}

$year = (int) $_GET['year'];
$month = (int) $_GET['month'];

$stmt = $pdo->prepare("
    SELECT 
        c.name AS category,
        SUM(e.amount) AS total_spent
    FROM expenses e
    JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ?
      AND YEAR(e.created_at) = ?
      AND MONTH(e.created_at) = ?
    GROUP BY c.id
    ORDER BY total_spent DESC
");
$stmt->execute([$user_id, $year, $month]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data);
exit;
