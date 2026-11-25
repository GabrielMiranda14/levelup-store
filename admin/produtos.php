<?php

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/database.php';

// Busca os produtos e suas categorias
$sql = "SELECT
            produtos.id,
            produtos.nome,
            produtos.preco,
            produtos.estoque,
            produtos.ativo,
            categorias.nome AS categoria
        FROM produtos
        INNER JOIN categorias
            ON produtos.categoria_id = categorias.id
        ORDER BY produtos.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';

?>

<main>

    <section class="admin-page container">

        <div class="section-heading">
            <span>GERENCIAMENTO</span>
            <h1>Produtos</h1>
            <p>Gerencie os produtos cadastrados na LevelUp Store.</p>
        </div>

        <div class="admin-products-header">

            <h2>Produtos cadastrados</h2>

            <a href="produto-novo.php" class="admin-button">
                + Novo produto
            </a>

        </div>

        <div class="admin-products-list">

            <?php if (empty($produtos)): ?>

                <p>Nenhum produto cadastrado.</p>

            <?php else: ?>

                <?php foreach ($produtos as $produto): ?>

                    <div class="admin-product-item">

                        <div class="admin-product-info">

                            <h3>
                                <?php echo htmlspecialchars($produto['nome']); ?>
                            </h3>

                            <p>
                                Categoria:
                                <?php echo htmlspecialchars($produto['categoria']); ?>
                            </p>

                            <p>
                                Preço:
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </p>

                            <p>
                                Estoque:
                                <?php echo (int) $produto['estoque']; ?> unidades
                            </p>

                            <span>
                                <?php echo $produto['ativo'] ? 'Ativo' : 'Inativo'; ?>
                            </span>

                        </div>

                        <div class="admin-product-actions">

                            <a href="produto-editar.php?id=<?php echo (int) $produto['id']; ?>">
                                Editar
                            </a>

                            <a href="produto-excluir.php?id=<?php echo (int) $produto['id']; ?>">
                                Excluir
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

        <a href="index.php" class="admin-back-link">
            ← Voltar ao painel
        </a>

    </section>

</main>

<?php require_once '../includes/footer.php'; ?>