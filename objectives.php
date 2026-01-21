<?php include 'includes/header.php'; ?>

<main class="objectives-page">
    <section class="objectives-hero">
        <h1>Objetivos de ahorro</h1>
        <p>Cada paso cuenta para alcanzar tus metas</p>
    </section>
    <section class="objectives-actions">
        <button class="button-app" data-bs-toggle="modal" data-bs-target="#addObjectiveModal">
            + Nuevo objetivo
        </button>
    </section>
    <section class="objectives-list">
        <div class="objective-card">
            <h2>Viaje a Japón</h2>
            <p class="objective-amount">1.200€ / 3.000€</p>
            <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
            </div>
            <p class="objective-date">Fecha objetivo: Dic 2026</p>
            <div class="objective-actions">
                <button class="button-app">Añadir dinero</button>
                <button class="button-app secondary">Editar</button>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>