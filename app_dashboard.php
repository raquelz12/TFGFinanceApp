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
            <button type="button" class="button-app" data-bs-toggle="modal" data-bs-target="#Modal">
            + Añadir Gasto
            </button>
            <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="ModalAddTransaction" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="ModalAddTransaction">Añade un gasto</h1>
                            <button type="button button-app" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h5>Descripción</h5>
                            <input type="text" placeholder="Descripción del gasto" class="form-control mb-2">
                            <h5>Categoría</h5>
                            <select class="form-control mb-2">
                                <option>Alimentación</option>
                                <option>Transporte</option>
                                <option>Ocio</option>
                                <option>Salud</option>
                                <option>Casa</option>
                                <option>Otros</option>
                            </select>
                            <h5>Cantidad</h5>
                            <input type="number" placeholder="€" class="form-control mb-2">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="button-app" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="button-app" data-bs-dismiss="modal">Guardar gasto</button>
                        </div>
                    </div>
                </div>
            </div>
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
