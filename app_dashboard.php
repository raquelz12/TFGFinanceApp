 <?php include 'includes/header.php'; ?>
    <main class="dashboard">
        <section class="dashboard-hero">
            <h1>Bienvenido de nuevo</h1>
            <p>Tu resumen financiero del mes</p>
        </section>
        <section class="cards-section">
            <div class="card">
                <div class="card-body">
                    <h2>Balance Actual</h2>
                    <p class="balance-amount">4.320€</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3>Ingresos</h3>
                    <p class="amount">1.250€</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3>Gastos</h3>
                    <p class="amount negative">-830€</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3>Ahorro</h3>
                    <p class="amount">420€</p>
                </div>
            </div>
        </section>
        <section class="chart-section">
            <h2>Balance mensual</h2>
            <div class="chart-card">
            </div>
        </section>
        <section class="transactions-section">
            <h2>Últimas transacciones</h2>
            <div class="transaction-list">
            </div>
        </section>
    </main>
<?php include 'includes/footer.php'; ?>