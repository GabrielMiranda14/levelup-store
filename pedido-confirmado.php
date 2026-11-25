<?php

session_start();

require_once 'config/database.php';

// Verifica se existe um pedido recente na sessão
if (!isset($_SESSION['ultimo_pedido']) || !isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$pedidoId = (int) $_SESSION['ultimo_pedido'];

// Busca os dados do pedido pertencente ao usuário conectado
$sql = "SELECT id, data_pedido, forma_pagamento, valor_total, status
        FROM pedidos
        WHERE id = :id AND usuario_id = :usuario_id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $pedidoId, PDO::PARAM_INT);
$stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
$stmt->execute();

$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    header('Location: index.php');
    exit;
}

unset($_SESSION['ultimo_pedido']);

require_once 'includes/header.php';

?>

<main>

    <section class="order-success-page container">

        <div class="order-success-card">

            <div class="success-icon">
                ✓
            </div>

            <span>PEDIDO REALIZADO</span>

            <h1>Compra concluída!</h1>

            <p>
                Obrigado por comprar na LevelUp Store.
                Seu pedido foi registrado com sucesso.
            </p>

            <div class="order-information">

                <div>
                    <span>Número do pedido</span>
                    <strong>#<?php echo (int) $pedido['id']; ?></strong>
                </div>

                <div>
                    <span>Forma de pagamento</span>
                    <strong>
                        <?php echo htmlspecialchars(ucfirst($pedido['forma_pagamento'])); ?>
                    </strong>
                </div>

                <div>
                    <span>Valor total</span>
                    <strong>
                        R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?>
                    </strong>
                </div>

                <div>
                    <span>Status</span>
                    <strong>
                        <?php echo htmlspecialchars(ucfirst($pedido['status'])); ?>
                    </strong>
                </div>

            </div>

            <a href="produtos.php" class="checkout-button">
                Continuar comprando
            </a>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>