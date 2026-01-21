<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance App</title>
    <link rel="stylesheet" href="public/css/main.css">
    <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">
    <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous">
    </script>
    <script 
    src="https://kit.fontawesome.com/c7ca284d32.js" 
    crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="public/js/app.js"></script>
</head>
<header>
    <nav class="navbar navbar-expand-lg sticky-top finance-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="app_dashboard.php">
                <i class="fa-solid fa-money-bills"></i>
                Finance App
            </a>
            <div class="navbar-profile">
                <a href="login.php" class="profile-btn" title="Mi Perfil">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>
            <div class="navbar-add-transaction">
                <a href="add_transaction.php" class="add-transaction-btn" title="Añadir Gasto" data-bs-toggle="modal" data-bs-target="#Modal">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="app_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="statistics.php">Estadísticas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="objectives.php">Objetivos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<div class="modal fade" id="NavBarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Añade un gasto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <select class="form-control mb-2">
                    <option>Alimentación</option>
                    <option>Transporte</option>
                    <option>Ocio</option>
                    <option>Salud</option>
                    <option>Casa</option>
                    <option>Otros</option>
                </select>
                <input type="number" placeholder="Cantidad (€)" class="form-control mb-2">
            </div>
            <div class="modal-footer">
                <button type="button" class="button-app" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="button-app" data-bs-dismiss="modal">Guardar gasto</button>
            </div>
        </div>
    </div>
</div>
