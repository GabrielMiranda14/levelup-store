<?php

require_once 'includes/header.php';

$carrinho = $_SESSION['carrinho'] ?? [];

$total = 0;

?>

<main>

    <section class="cart-page container">

        <div class="section-heading">
            <span>SUA SELEÇÃO</span>
            <h1>Meu carrinho</h1>
            <p>Confira os produtos escolhidos antes de continuar.</p>
        </div>

        <?php if (count($carrinho) > 0): ?>

            <?php foreach ($carrinho as $item): ?>

                <?php
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total += $subtotal;
                ?>

                <div class="cart-item">

                    <img
                        src="<?php echo htmlspecialchars($item['imagem']); ?>"
                        alt="<?php echo htmlspecialchars($item['nome']); ?>"
                    >

                    <div class="cart-item-info">

                        <h3>
                            <?php echo htmlspecialchars($item['nome']); ?>
                        </h3>

                        <form action="atualizar-carrinho.php" method="POST" class="cart-actions">

                            <input
                                type="hidden"
                                name="produto_id"
                                value="<?php echo (int) $item['id']; ?>"
                            >

                            <input
                                type="number"
                                name="quantidade"
                                value="<?php echo (int) $item['quantidade']; ?>"
                                min="1"
                            >

                            <button type="submit">Atualizar</button>

                            <button
                                type="submit"
                                formaction="remover-carrinho.php"
                                formmethod="POST"
                            >
                                Remover
                            </button>

                        </form>

                        <strong>
                            R$ <?php echo number_format($subtotal, 2, ',', '.'); ?>
                        </strong>

                    </div>

                </div>

            <?php endforeach; ?>

            <div class="cart-total">
                <h2>
                    Total: R$ <?php echo number_format($total, 2, ',', '.'); ?>
                </h2>
            </div>

            <div class="cart-checkout">
                <a href="checkout.php" class="checkout-button">
                    Finalizar compra
                </a>
            </div>

        <?php else: ?>

            <p class="empty-cart">Seu carrinho está vazio.</p>

        <?php endif; ?>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>