
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LevelUp Store | Games e Tecnologia</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="/LevelUp%20Store/assets/css/style.css">
</head>

<body>

<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <a class="navbar-brand" href="/LevelUp%20Store/index.php">
                LevelUp<span>Store</span>
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal"
                aria-expanded="false"
                aria-label="Abrir menu">

                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuPrincipal">

                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/LevelUp%20Store/index.php">Início</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/LevelUp%20Store/produtos.php">Produtos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Categorias</a>
                    </li>
                </ul>

                <div class="d-flex gap-3">
                    <a class="nav-link" href="#">Minha conta</a>
                    <a class="nav-link" href="#">Carrinho</a>
                </div>

            </div>
        </div>
    </nav>
</header>