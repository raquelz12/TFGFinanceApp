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
        <div class="chart-wrapper">
            <div class="chart-card">
                <canvas id="gastosChart"></canvas>
            </div>
            <div class="chart-stats">
                <div class="card">
                    <h2>Mayor gasto</h2>
                    <p>Alimentación - 300€</p>
                </div>
                <div class="card">
                    <h2>Menor gasto</h2>
                    <p>Otros - 100€</p>
                </div>
                <div class="card">
                    <h2>Porcentaje de gasto por categoría más alta</h2>
                    <p>Alimentación - 36% del total</p>
                </div>
                <div class="card">
                    <h2>Cantidad de transacciones en la categoría principal</h2>
                    <p>5 compras en Alimentación este mes</p>
                </div>
                <div class="card">
                    <h2>Transacción más cara dentro del mes</h2>
                    <p>Supermercado - 42€</p>
                </div>
            </div>
        </div>
    </section>
    <section class="transactions-section">
        <div class="transactions-header">
            <h2>Últimos gastos</h2>
            <button type="button" class="button-app" data-bs-toggle="modal" data-bs-target="#ModalAddTransaction">
                + Añadir Gasto
            </button>
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
