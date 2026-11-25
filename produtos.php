<?php

require_once 'config/database.php';
$sqlCategorias = "SELECT * FROM categorias ORDER BY nome ASC";
$stmtCategorias = $pdo->prepare($sqlCategorias);
$stmtCategorias->execute();

$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
require_once 'includes/header.php';

$categoria = $_GET['categoria'] ?? null;
$busca = $_GET['busca'] ?? '';

$sql = "SELECT * FROM produtos WHERE ativo = 1";
$parametros = [];

if ($categoria !== null && is_numeric($categoria)) {
    $sql .= " AND categoria_id = :categoria";
    $parametros[':categoria'] = (int) $categoria;
}

if (!empty($busca)) {
    $sql .= " AND nome LIKE :busca";
    $parametros[':busca'] = '%' . $busca . '%';
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);

foreach ($parametros as $chave => $valor) {
    $stmt->bindValue($chave, $valor);
}

$stmt->execute();

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<main>

    <section class="products-page container">

        <div class="section-heading">
            <span>EXPLORE NOSSA LOJA</span>
            <h1>Todos os produtos</h1>
            <p>Encontre os equipamentos e acessórios ideais para elevar seu setup.</p>
        </div>

        <form action="produtos.php" method="GET" class="search-form">

            <?php if ($categoria !== null && is_numeric($categoria)): ?>
                <input type="hidden" name="categoria" value="<?php echo (int) $categoria; ?>">
            <?php endif; ?>

            <input
                type="text"
                name="busca"
                placeholder="Pesquisar produto..."
                value="<?php echo htmlspecialchars($busca); ?>"
            >

            <button type="submit">Buscar</button>

        </form>

        <div class="category-filters">

            <a href="produtos.php" class="filter-link">
                Todos
            </a>

            <?php foreach ($categorias as $categoriaItem): ?>

                <a
                    href="produtos.php?categoria=<?php echo $categoriaItem['id']; ?>"
                    class="filter-link"
                >
                    <?php echo htmlspecialchars($categoriaItem['nome']); ?>
                </a>

            <?php endforeach; ?>

        </div>

        <div class="products-grid">

            <?php if (count($produtos) > 0): ?>

                <?php foreach ($produtos as $produto): ?>

                    <div class="product-card">

                        <div class="product-image">
                            <img
                                src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                            >
                        </div>

                        <div class="product-info">

                            <h3>
                                <?php echo htmlspecialchars($produto['nome']); ?>
                            </h3>

                            <p>
                                <?php echo htmlspecialchars($produto['descricao']); ?>
                            </p>

                            <strong>
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </strong>

                            <a
                                href="produto.php?id=<?php echo (int) $produto['id']; ?>"
                                class="view-product-button"
                            >
                                Ver detalhes
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p>Nenhum produto disponível no momento.</p>

            <?php endif; ?>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>