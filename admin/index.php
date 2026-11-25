<?php

session_start();

// Verifica se o usuário está conectado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

// Verifica se o usuário é administrador
if ($_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/database.php';

?>

<?php require_once '../includes/header.php'; ?>

<main>

    <section class="admin-page container">

        <div class="section-heading">
            <span>LEVELUP STORE</span>
            <h1>Painel administrativo</h1>
            <p>Gerencie os produtos e pedidos da loja.</p>
        </div>

        <div class="admin-options">

            <a href="produtos.php" class="admin-card">
                <h2>Produtos</h2>
                <p>Cadastrar, editar e excluir produtos.</p>
                <span>Gerenciar produtos →</span>
            </a>

            <a href="pedidos.php" class="admin-card">
                <h2>Pedidos</h2>
                <p>Consultar os pedidos realizados pelos clientes.</p>
                <span>Visualizar pedidos →</span>
            </a>

        </div>

    </section>

</main>

<?php require_once '../includes/footer.php'; ?>