
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
    <link rel="stylesheet" href="/levelup-store/assets/css/style.css">
</head>

<body>

<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <a class="navbar-brand" href="/levelup-store/index.php">
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
                        <a class="nav-link" href="/levelup-store/index.php">Início</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/levelup-store/produtos.php">Produtos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/levelup-store/produtos.php#categorias">Categorias</a>
                    </li>
                </ul>

                <div class="d-flex gap-3 align-items-center">

                    <?php if (isset($_SESSION['usuario_id'])): ?>

                        <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
                            <a class="nav-link" href="/levelup-store/admin/">
                                Painel administrativo
                            </a>
                        <?php endif; ?>

                        <span class="nav-link">
                            Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>
                        </span>

                        <a class="nav-link" href="/levelup-store/logout.php">
                            Sair
                        </a>

                    <?php else: ?>

                        <a class="nav-link" href="/levelup-store/login.php">
                            Minha conta
                        </a>

                    <?php endif; ?>

                    <a class="nav-link" href="/levelup-store/carrinho.php">
                        Carrinho
                    </a>

                </div>

            </div>
        </div>
    </nav>
</header>