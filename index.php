<?php

require_once 'config/database.php';
require_once 'includes/header.php';

$sql = "SELECT * FROM produtos WHERE ativo = 1 ORDER BY id DESC LIMIT 4";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<main>

    <section class="hero container">

        <div class="hero-content">

            <span class="hero-tag">LEVEL UP YOUR GAME</span>

            <h1>
                Eleve sua experiência
                <span>ao próximo nível.</span>
            </h1>

            <p>
                Descubra produtos de games e tecnologia
                selecionados para transformar sua experiência.
            </p>

            <a href="produtos.php" class="btn-hero">
                Explorar produtos
            </a>

        </div>

        <div class="hero-image">
            <img src="assets/images/hero-gamer.jpg" alt="Setup gamer com iluminação RGB">
        </div>

    </section>

    <section class="categories container">

        <div class="section-heading">
            <span>EXPLORE A LOJA</span>
            <h2>Encontre sua categoria</h2>
            <p>Descubra os produtos ideais para sua experiência gamer.</p>
        </div>

        <div class="categories-grid">

            <a href="produtos.php?categoria=1" class="category-card">
                <img src="assets/images/categories/consoles.jpg" alt="Consoles">
                <div class="category-card-content">
                    <h3>Consoles</h3>
                    <p>Explore os consoles</p>
                </div>
            </a>

            <a href="produtos.php?categoria=2" class="category-card">
                <img src="assets/images/categories/perifericos.jpg" alt="Periféricos gamer">
                <div class="category-card-content">
                    <h3>Periféricos</h3>
                    <p>Melhore seu setup</p>
                </div>
            </a>

            <a href="produtos.php?categoria=3" class="category-card">
                <img src="assets/images/categories/hardwares.jpg" alt="Componentes de hardware">
                <div class="category-card-content">
                    <h3>Hardware</h3>
                    <p>Potencialize seu PC</p>
                </div>
            </a>

            <a href="produtos.php?categoria=4" class="category-card">
                <img src="assets/images/categories/jogos.jpg" alt="Jogos">
                <div class="category-card-content">
                    <h3>Jogos</h3>
                    <p>Novas aventuras esperam</p>
                </div>
            </a>

            <a href="produtos.php?categoria=5" class="category-card">
                <img src="assets/images/categories/acessorios.jpg" alt="Acessórios gamer">
                <div class="category-card-content">
                    <h3>Acessórios</h3>
                    <p>Complete sua experiência</p>
                </div>
            </a>

        </div>

    </section>

    <section class="featured-products container">

        <div class="section-heading">
            <span>SELEÇÃO ESPECIAL</span>
            <h2>Produtos em destaque</h2>
            <p>Confira alguns dos produtos disponíveis em nossa loja.</p>
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

