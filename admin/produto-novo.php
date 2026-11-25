<?php

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/database.php';

// Busca as categorias disponíveis
$sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = $_POST['preco'] ?? '';
    $estoque = $_POST['estoque'] ?? '';
    $categoriaId = $_POST['categoria_id'] ?? '';
    $imagem = trim($_POST['imagem'] ?? '');

    if (
        $nome === '' ||
        $descricao === '' ||
        !is_numeric($preco) ||
        $preco < 0 ||
        filter_var($estoque, FILTER_VALIDATE_INT) === false ||
        $estoque < 0 ||
        !ctype_digit((string) $categoriaId)
    ) {
        $erro = 'Preencha todos os campos corretamente.';
    } else {

        // Verifica se a categoria existe
        $sql = "SELECT id FROM categorias WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', (int) $categoriaId, PDO::PARAM_INT);
        $stmt->execute();

        if (!$stmt->fetch()) {
            $erro = 'Categoria inválida.';
        } else {

            // Cadastra o produto
            $sql = "INSERT INTO produtos
                    (categoria_id, nome, descricao, preco, estoque, imagem, ativo)
                    VALUES
                    (:categoria_id, :nome, :descricao, :preco, :estoque, :imagem, 1)";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':categoria_id', (int) $categoriaId, PDO::PARAM_INT);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':preco', $preco);
            $stmt->bindValue(':estoque', (int) $estoque, PDO::PARAM_INT);
            $stmt->bindValue(':imagem', $imagem);

            $stmt->execute();

            header('Location: produtos.php?cadastro=sucesso');
            exit;
        }
    }
}

require_once '../includes/header.php';

?>

<main>

    <section class="admin-page container">

        <div class="section-heading">
            <span>GERENCIAMENTO</span>
            <h1>Novo produto</h1>
            <p>Adicione um novo produto à LevelUp Store.</p>
        </div>

        <div class="admin-form-container">

            <?php if ($erro !== ''): ?>
                <p class="form-error">
                    <?php echo htmlspecialchars($erro); ?>
                </p>
            <?php endif; ?>

            <form method="POST" class="admin-form">

                <div class="form-group">
                    <label for="nome">Nome do produto</label>
                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea
                        id="descricao"
                        name="descricao"
                        rows="5"
                        required
                    ><?php echo htmlspecialchars($_POST['descricao'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoria</label>
                    <select id="categoria_id" name="categoria_id" required>
                        <option value="">Selecione uma categoria</option>

                        <?php foreach ($categorias as $categoria): ?>
                            <option
                                value="<?php echo (int) $categoria['id']; ?>"
                                <?php echo (($_POST['categoria_id'] ?? '') == $categoria['id']) ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($categoria['nome']); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="preco">Preço (R$)</label>
                    <input
                        type="number"
                        id="preco"
                        name="preco"
                        step="0.01"
                        min="0"
                        value="<?php echo htmlspecialchars($_POST['preco'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="estoque">Quantidade em estoque</label>
                    <input
                        type="number"
                        id="estoque"
                        name="estoque"
                        min="0"
                        step="1"
                        value="<?php echo htmlspecialchars($_POST['estoque'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="imagem">Caminho da imagem</label>
                    <input
                        type="text"
                        id="imagem"
                        name="imagem"
                        placeholder="assets/images/products/produto.jpg"
                        value="<?php echo htmlspecialchars($_POST['imagem'] ?? ''); ?>"
                    >
                </div>

                <button type="submit" class="admin-button">
                    Cadastrar produto
                </button>

                <a href="produtos.php" class="admin-back-link">
                    Cancelar
                </a>

            </form>

        </div>

    </section>

</main>

<?php require_once '../includes/footer.php'; ?>