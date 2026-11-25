<?php

require_once 'config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Produto não encontrado.");
}

$sql = "SELECT * FROM produtos WHERE id = :id AND ativo = 1";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$produto = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produto) {
    die("Produto não encontrado ou indisponível.");
}

require_once 'includes/header.php';

?>

<main>

    <section class="product-details container">

        <div class="product-details-image">
            <img
                src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                alt="<?php echo htmlspecialchars($produto['nome']); ?>"
            >
        </div>

        <div class="product-details-info">

            <span class="product-label">PRODUTO LEVELUP</span>

            <h1>
                <?php echo htmlspecialchars($produto['nome']); ?>
            </h1>

            <p class="product-description">
                <?php echo htmlspecialchars($produto['descricao']); ?>
            </p>

            <strong class="product-price">
                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
            </strong>

            <p class="product-stock">
                Estoque disponível:
                <?php echo (int) $produto['estoque']; ?> unidades
            </p>

            <form action="adicionar-carrinho.php" method="POST">

                <input
                    type="hidden"
                    name="produto_id"
                    value="<?php echo (int) $produto['id']; ?>"
                >

                <button type="submit" class="add-cart-button">
                    Adicionar ao carrinho
                </button>

            </form>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>