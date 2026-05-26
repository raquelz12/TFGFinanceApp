<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$user_id = $_SESSION['user_id'];

if (!isset($_GET['year'], $_GET['month'])) {
    header("Location: history.php");
    exit;
}

$year  = (int) $_GET['year'];
$month = (int) $_GET['month'];
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 4;
$offset = ($page - 1) * $perPage;
$order = isset($_GET['order']) && strtolower($_GET['order']) === 'asc' ? 'ASC' : 'DESC';
$sort = 'created_at';

$stmt = $pdo->prepare("
    SELECT SUM(amount) as total_month
    FROM expenses
    WHERE user_id = ?
      AND YEAR(created_at) = ?
      AND MONTH(created_at) = ?
");
$stmt->execute([$user_id, $year, $month]);
$total_month = (float) $stmt->fetchColumn();

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$avg_per_day = $days_in_month > 0 ? $total_month / $days_in_month : 0;

$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM expenses
    WHERE user_id = ?
      AND YEAR(created_at) = ?
      AND MONTH(created_at) = ?
");
$stmt->execute([$user_id, $year, $month]);
$total_records = (int) $stmt->fetchColumn();
$total_pages = ceil($total_records / $perPage);

$stmt = $pdo->prepare("
    SELECT e.id, e.name, e.amount, e.created_at, c.name AS category
    FROM expenses e
    LEFT JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ?
      AND YEAR(e.created_at) = ?
      AND MONTH(e.created_at) = ?
    ORDER BY e.created_at $order
    LIMIT ? OFFSET ?
");

$stmt->bindValue(1, $user_id, PDO::PARAM_INT);
$stmt->bindValue(2, $year, PDO::PARAM_INT);
$stmt->bindValue(3, $month, PDO::PARAM_INT);
$stmt->bindValue(4, $perPage, PDO::PARAM_INT);
$stmt->bindValue(5, $offset, PDO::PARAM_INT);
$stmt->execute();
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$date = DateTime::createFromFormat('Y-n', $year . '-' . $month);

include 'includes/header.php';
?>

<main class="history-page">
    <section class="history-hero">
        <h1><?= $date->format('F Y') ?></h1>
        <p>Gastos y estadísticas del mes</p>
    </section>
    <section class="history-section">
        <div class="history-card">
            <h2>Total gastado</h2>
            <p class="amount"><?= number_format($total_month, 2) ?> €</p>
        </div>
        <div class="history-card">
            <h2>Media diaria</h2>
            <p class="amount"><?= number_format($avg_per_day, 2) ?> €</p>
        </div>
    </section>
    <section class="history-expenses">
        <h2>Gastos del mes</h2>
        <?php if (empty($expenses)): ?>
            <p>No hay gastos registrados en este mes.</p>
        <?php else: ?>
            <div class="history-expenses-list">
                <div class="history-expenses-header history-expenses-item">
                    <span>Día</span>
                    <span>Nombre</span>
                    <span>Categoría</span>
                    <span>Importe</span>
                </div>
                <?php foreach ($expenses as $exp): ?>
                        <div class="history-expenses-item">
                            <span><?= date('d', strtotime($exp['created_at'])) ?></span>
                            <span><?= htmlspecialchars($exp['name']) ?></span>
                            <span><?= htmlspecialchars($exp['category'] ?? 'Sin categoría') ?></span>
                            <span><?= number_format($exp['amount'], 2) ?> €</span>
                        </div>
                <?php endforeach; ?>
            </div>
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?year=<?= $year ?>&month=<?= $month ?>&page=<?= $i ?>&order=<?= strtolower($order) ?>"
                        class="<?= $i == $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <div class="history-back">
        <button onclick="window.location.href='history.php'" class="button-app">Volver al historial</button>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
