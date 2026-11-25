<?php

session_start();

require_once 'config/database.php';

// Verifica se o usuário está conectado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verifica se o carrinho possui produtos
$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($carrinho)) {
    header('Location: carrinho.php');
    exit;
}

$total = 0;

foreach ($carrinho as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

require_once 'includes/header.php';

?>

<main>

    <section class="checkout-page container">

        <div class="section-heading">
            <span>QUASE LÁ!</span>
            <h1>Finalizar compra</h1>
            <p>Confira seu pedido e escolha a forma de pagamento.</p>
        </div>

        <div class="checkout-content">

            <div class="checkout-products">

                <h2>Resumo do pedido</h2>

                <?php foreach ($carrinho as $item): ?>

                    <div class="checkout-item">

                        <img
                            src="<?php echo htmlspecialchars($item['imagem']); ?>"
                            alt="<?php echo htmlspecialchars($item['nome']); ?>"
                        >

                        <div class="checkout-item-info">
                            <h3><?php echo htmlspecialchars($item['nome']); ?></h3>

                            <p>Quantidade: <?php echo (int) $item['quantidade']; ?></p>

                            <strong>
                                R$ <?php echo number_format(
                                    $item['preco'] * $item['quantidade'],
                                    2,
                                    ',',
                                    '.'
                                ); ?>
                            </strong>
                        </div>

                    </div>

                <?php endforeach; ?>

                <div class="checkout-total">
                    <h3>
                        Total:
                        R$ <?php echo number_format($total, 2, ',', '.'); ?>
                    </h3>
                </div>

            </div>

            <div class="checkout-payment">

                <h2>Forma de pagamento</h2>

                <form action="processar-pedido.php" method="POST">

                    <label class="payment-option">
                        <input type="radio" name="forma_pagamento" value="pix" required>
                        PIX
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="forma_pagamento" value="boleto">
                        Boleto bancário
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="forma_pagamento" value="cartao">
                        Cartão de crédito
                    </label>

                    <button type="submit" class="checkout-button">
                        Confirmar pedido
                    </button>

                </form>

            </div>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>