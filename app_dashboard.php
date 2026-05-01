<?php include 'includes/header.php'; ?>

<main class="dashboard">
    <section class="dashboard-hero">
        <h1>Tu Panel de Gastos</h1>
        <p>Visualiza y controla tus gastos del mes</p>
    </section>
    <section class="cards-section">
        <div class="card">
            <div class="card-body">
                <h2>Gasto total del mes</h2>
                <p class="amount negative">830€</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2>Gasto promedio diario</h2>
                <p class="amount">27€</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2>Ahorro estimado</h2>
                <p class="amount positive">420€</p>
            </div>
        </div>
    </section>
    <section class="chart-section">
        <h2>Gastos por categoría</h2>
        <div class="chart-card">
            <canvas id="gastosChart"></canvas>
        </div>
    </section>
    <section class="transactions-section">
        <div class="transactions-header">
            <h2>Últimos gastos</h2>
            <button class="add-btn">+ Añadir gasto</button>
        </div>
        <div class="transaction-list">
            <div class="transaction-item">
                <span class="t-desc">Supermercado</span>
                <span class="t-cat">Alimentación</span>
                <span class="t-amount">-42€</span>
            </div>
            <div class="transaction-item">
                <span class="t-desc">Autobús</span>
                <span class="t-cat">Transporte</span>
                <span class="t-amount">-2.80€</span>
            </div>
            <div class="transaction-item">
                <span class="t-desc">Cena con amigos</span>
                <span class="t-cat">Ocio</span>
                <span class="t-amount">-18€</span>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
