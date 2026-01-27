<?php

http_response_code(404);
require_once __DIR__ . '/bootstrap.php';
include 'includes/header.php';
?>

<main class="error-page">
    <div class="container">
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>La página que buscas no existe o ha sido movida.</p>
        <div class="error-actions">
            <button onclick="history.back()" class="button-app primary">Volver</button>
            <button onclick="window.location.href='app_dashboard.php'" class="button-app secondary">Ir al inicio</button>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
