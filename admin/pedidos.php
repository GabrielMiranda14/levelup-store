
<?php

session_start();

// Verifica se o usuário é administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/database.php';

// Busca os pedidos e os dados dos clientes
$sql = "SELECT
            pedidos.id,
            pedidos.data_pedido,
            pedidos.forma_pagamento,
            pedidos.valor_total,
            pedidos.status,
            usuarios.nome AS cliente
        FROM pedidos
        INNER JOIN usuarios
            ON pedidos.usuario_id = usuarios.id
        ORDER BY pedidos.data_pedido DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';

?>

<main>

    <section class="admin-page container">

        <div class="section-heading">
            <span>LEVELUP STORE</span>
            <h1>Gerenciar pedidos</h1>
            <p>Consulte os pedidos realizados pelos clientes.</p>
        </div>

        <div class="admin-products-header">
            <h2>Pedidos realizados</h2>

            <a href="index.php" class="admin-back-link">
                Voltar ao painel
            </a>
        </div>

        <div class="admin-products-list">

            <?php if (empty($pedidos)): ?>

                <p>Nenhum pedido foi realizado ainda.</p>

            <?php else: ?>

                <?php foreach ($pedidos as $pedido): ?>

                    <div class="admin-product-item">

                        <div class="admin-product-info">

                            <h3>Pedido #<?php echo $pedido['id']; ?></h3>

                            <p>
                                Cliente:
                                <?php echo htmlspecialchars($pedido['cliente']); ?>
                            </p>

                            <p>
                                Data:
                                <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?>
                            </p>

                            <p>
                                Pagamento:
                                <?php echo strtoupper($pedido['forma_pagamento']); ?>
                            </p>

                            <p>
                                Total:
                                R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?>
                            </p>

                            <span>
                                <?php echo ucfirst($pedido['status']); ?>
                            </span>

                        </div>

                        <div class="admin-product-actions">

                            <a href="pedido-detalhes.php?id=<?php echo $pedido['id']; ?>">
                                Ver detalhes
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </section>

</main>

<?php require_once '../includes/footer.php'; ?>
