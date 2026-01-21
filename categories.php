<?php include 'includes/header.php'; ?>
<main class="app-main">
    <section class="categories-section">
        <h2>Categorías de gastos</h2>
        <div class="categories">
            <div class="category-progress">
                <div class="card-header">
                    <h4>Alimentación</h4>
                    <button class="edit-category-button" title="Editar límite mensual" data-bs-toggle="modal" data-bs-target="#ModalEditarLimite"><i class="fa-solid fa-pencil"></i></button>
                    <div class="modal fade" id="ModalEditarLimite" tabindex="-1" aria-labelledby="ModalEditarLimite" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="ModalEditarLimite">Editar límite mensual</h1>
                                    <button type="button button-app" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h5>Límite Mensual</h5>
                                    <input type="number" placeholder="Cantidad (€)" class="form-control mb-2">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="button-app" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" class="button-app" data-bs-dismiss="modal">Editar límite</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" data-amount="200" data-budget="200"></div>
                </div>
                <p class="progress-info">200€ / 200€</p>
            </div>
            <div class="category-progress">
                <div class="card-header">
                    <h4>Transporte</h4>
                    <button class="edit-category-button" title="Editar límite mensual" data-bs-toggle="modal" data-bs-target="#ModalEditarLimite"><i class="fa-solid fa-pencil"></i></button>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" data-amount="80" data-budget="150"></div>
                </div>
                <p class="progress-info">80€ / 150€</p>
            </div>
            <div class="category-progress">
                <div class="card-header">
                    <h4>Ocio</h4>
                    <button class="edit-category-button" title="Editar límite mensual" data-bs-toggle="modal" data-bs-target="#ModalEditarLimite"><i class="fa-solid fa-pencil"></i></button>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" data-amount="50" data-budget="100"></div>
                </div>
                <p class="progress-info">50€ / 100€</p>
            </div>
            <div class="category-progress">
                <div class="card-header">
                    <h4>Salud</h4>
                    <button class="edit-category-button" title="Editar límite mensual" data-bs-toggle="modal" data-bs-target="#ModalEditarLimite"><i class="fa-solid fa-pencil"></i></button>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" data-amount="50" data-budget="100"></div>
                </div>
                <p class="progress-info">50€ / 100€</p>
            </div>
            <div class="category-progress">
                <div class="card-header">
                    <h4>Vivienda</h4>
                    <button class="edit-category-button" title="Editar límite mensual" data-bs-toggle="modal" data-bs-target="#ModalEditarLimite"><i class="fa-solid fa-pencil"></i></button>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" data-amount="370" data-budget="400"></div>
                </div>
                <p class="progress-info">370€ / 400€</p>
            </div>
            <div class="category-progress">
                <div class="card-header">
                    <h4>Otros</h4>
                    <button class="edit-category-button" title="Editar límite mensual" data-bs-toggle="modal" data-bs-target="#ModalEditarLimite"><i class="fa-solid fa-pencil"></i></button>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" data-amount="50" data-budget="100"></div>
                </div>
                <p class="progress-info">50€ / 100€</p>
            </div>
        </div>
    </section>
</main>
<?php include 'includes/footer.php'; ?>