<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$user_id = $_SESSION['user_id'];
$order = ($_GET['order'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
    SELECT COUNT(*) AS total_months
    FROM (
        SELECT YEAR(created_at) AS year, MONTH(created_at) AS month
        FROM expenses
        WHERE user_id = ?
        GROUP BY YEAR(created_at), MONTH(created_at)
    ) AS sub
");
$stmt->execute([$user_id]);
$total_months = (int) $stmt->fetchColumn();
$total_pages = ceil($total_months / $perPage);

$stmt = $pdo->prepare("
    SELECT 
        YEAR(created_at) AS year,
        MONTH(created_at) AS month,
        SUM(amount) AS total_spent
    FROM expenses
    WHERE user_id = ?
    GROUP BY YEAR(created_at), MONTH(created_at)
    ORDER BY year $order, month $order
    LIMIT ? OFFSET ?
");
$stmt->bindValue(1, $user_id, PDO::PARAM_INT);
$stmt->bindValue(2, $perPage, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<main class="history-page">
    <section class="history-hero">
        <h1>Historial mensual</h1>
        <p>Revisa cómo han evolucionado tus gastos mes a mes</p>
    </section>
    <section class="history-controls">
        <form method="GET" class="sort-form">
            <label for="order">Ordenar por fecha:</label>
            <select name="order" id="order" onchange="this.form.submit()">
                <option value="desc" <?= $order === 'DESC' ? 'selected' : '' ?>>Más reciente primero</option>
                <option value="asc" <?= $order === 'ASC' ? 'selected' : '' ?>>Más antiguo primero</option>
            </select>
        </form>
    </section>
    <section class="history-section">
        <?php if (empty($history)): ?>
            <p>No hay datos de meses anteriores todavía.</p>
        <?php else: ?>
            <div class="history-list">
                <?php foreach ($history as $row): 
                    $date = DateTime::createFromFormat('Y-n', $row['year'].'-'.$row['month']);
                ?>
                <div class="history-card">
                    <div class="history-header">
                        <h2><?= $date->format('F Y') ?></h2>
                    </div>
                    <div class="history-body">
                        <p>
                            <span>Gasto total:</span>
                            <?= number_format($row['total_spent'], 2) ?> €
                        </p>
                    </div>
                    <div class="history-footer">
                        <button 
                            onclick="window.location.href='history_detailed.php?year=<?= $row['year'] ?>&month=<?= $row['month'] ?>'"
                            class="button-app"
                        >
                            Ver detalle

                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>&order=<?= strtolower($order) ?>"
                        class="<?= $i == $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
