<?php 
session_start();
require 'backend/config/connection.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$day   = date('d');
$month = date('m');
$year  = date('Y');

$stmt = $pdo->prepare("
    SELECT SUM(amount) AS total
    FROM expenses
    WHERE user_id = ?
      AND MONTH(created_at) = ?
      AND YEAR(created_at) = ?
");
$stmt->execute([$user_id, $month, $year]);
$total_month = $stmt->fetchColumn();
$total_month = $total_month ?: 0;
$average_daily = $total_month / $day;

$stmt = $pdo->prepare("
    SELECT e.name, e.amount, c.name AS category
    FROM expenses e
    JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ?
    ORDER BY e.created_at DESC
    LIMIT 3
");
$stmt->execute([$user_id]);
$recent_expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT 
        COALESCE(SUM(c.amount),0) - COALESCE(SUM(e.total_spent),0) AS estimated_saving
    FROM categories c
    LEFT JOIN (
        SELECT category_id, SUM(amount) AS total_spent
        FROM expenses
        WHERE user_id = ?
        GROUP BY category_id
    ) e ON c.id = e.category_id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id, $user_id]);
$estimated_saving = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT 
        c.name AS category,
        SUM(e.amount) AS total,
        COUNT(e.id) AS transactions,
        MAX(e.amount) AS max_expense
    FROM categories c
    LEFT JOIN expenses e 
        ON c.id = e.category_id
        AND e.user_id = ?
        AND MONTH(e.created_at) = ?
        AND YEAR(e.created_at) = ?
    WHERE c.user_id = ?
    GROUP BY c.id
");
$stmt->execute([$user_id, $month, $year, $user_id]);
$category_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$totals = [];

foreach ($category_data as $row) {
    if ($row['total'] > 0) {
        $labels[] = $row['category'];
        $totals[] = $row['total'];
    }
}

$max_category = null;
$min_category = null;
$max_value = 0;
$min_value = null;
$total_expenses = array_sum($totals);
$top_transactions = 0;
$top_transaction_amount = 0;

foreach ($category_data as $row) {
    if ($row['total'] > 0) {
        if ($row['total'] > $max_value) {
            $max_value = $row['total'];
            $max_category = $row['category'];
            $top_transactions = $row['transactions'];
            $top_transaction_amount = $row['max_expense'];
        }

        if ($min_value === null || $row['total'] < $min_value) {
            $min_value = $row['total'];
            $min_category = $row['category'];
        }
    }
}

$percentage = $total_expenses > 0 
    ? round(($max_value / $total_expenses) * 100) 
    : 0;


include 'includes/header.php'; ?>

<main class="dashboard">
    <script>
        const chartLabels = <?= json_encode($labels) ?>;
        const chartTotals = <?= json_encode($totals) ?>;
    </script>
    <section class="dashboard-hero">
        <?php echo "<h1>Bienvenid@, " . htmlspecialchars($_SESSION["user_name"]) . "!</h1>"; ?>
        <p>Visualiza y controla tus gastos del mes</p>
    </section>
    <section class="cards-section">
        <div class="card">
            <div class="card-body">
                <h2>Gasto total del mes</h2>
                <?php if (!isset($_SESSION['blur_amount']) || $_SESSION['blur_amount'] === false) : ?>
                    <p class="amount">
                        <?php
                        echo number_format($total_month, 2) 
                        ?>€
                    </p>
                    <?php else : ?>
                    <span class="blurred">****€</span>
                    <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2>Gasto promedio diario</h2>
                <?php if (!isset($_SESSION['blur_amount']) || $_SESSION['blur_amount'] === false) : ?>
                    <p class="amount">
                        <?php
                        echo number_format($average_daily, 2) 
                        ?>€
                    </p>
                <?php else : ?>
                    <span class="blurred">****€</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2>Ahorro estimado</h2>
                <?php if (!isset($_SESSION['blur_amount']) || $_SESSION['blur_amount'] === false) : ?>
                    <p class="amount">
                        <?php
                        echo number_format($estimated_saving, 2) 
                        ?>€
                    </p>
                <?php else : ?>
                    <span class="blurred">****€</span>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <section class="chart-section">
        <h2>Gastos por categoría</h2>
        <div class="chart-wrapper">
            <div class="chart-card">
                <canvas id="gastosChart"></canvas>
            </div>
            <div class="chart-stats">
                <div class="card">
                    <h2>Mayor gasto</h2>
                    <p><?= $max_category ?> - <?= number_format($max_value,2) ?>€</p>
                </div>

                <div class="card">
                    <h2>Menor gasto</h2>
                    <p><?= $min_category ?> - <?= number_format($min_value,2) ?>€</p>
                </div>

                <div class="card">
                    <h2>Porcentaje del gasto principal</h2>
                    <p><?= $max_category ?> - <?= $percentage ?>%</p>
                </div>

                <div class="card">
                    <h2>Transacciones en categoría principal</h2>
                    <p><?= $top_transactions ?> gastos este mes</p>
                </div>

                <div class="card">
                    <h2>Gasto más alto del mes</h2>
                    <p><?= number_format($top_transaction_amount,2) ?>€</p>
                </div>
            </div>
        </div>
    </section>
    <section class="transactions-section">
        <div class="transactions-header">
            <h2>Últimos gastos</h2>
            <button type="button" class="button-app" data-bs-toggle="modal" data-bs-target="#ModalAddExpense">
                + Añadir Gasto
            </button>
        </div>
        <div class="transaction-list">
            <?php foreach ($recent_expenses as $exp): ?>
            <div class="transaction-item">
                <span><?= htmlspecialchars($exp['name']) ?></span>
                <span><?= htmlspecialchars($exp['category']) ?></span>
                <span><?= number_format($exp['amount'],2,',','.') ?> €</span>
            </div>
        <?php endforeach; ?>
        </div>
        <button type="button" class="button-app view-more-btn" onclick="window.location.href='expenses.php'">
            Ver más gastos
        </button>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
