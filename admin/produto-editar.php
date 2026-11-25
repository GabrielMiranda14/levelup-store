<?php

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: produtos.php');
    exit;
}

// Busca o produto
$sql = "SELECT * FROM produtos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header('Location: produtos.php');
    exit;
}

// Busca as categorias
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
    $ativo = $_POST['ativo'] ?? '';

    if (
        $nome === '' ||
        $descricao === '' ||
        !is_numeric($preco) ||
        $preco < 0 ||
        filter_var($estoque, FILTER_VALIDATE_INT) === false ||
        $estoque < 0 ||
        !ctype_digit((string) $categoriaId) ||
        !in_array($ativo, ['0', '1'], true)
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

            // Atualiza o produto
            $sql = "UPDATE produtos SET
                        categoria_id = :categoria_id,
                        nome = :nome,
                        descricao = :descricao,
                        preco = :preco,
                        estoque = :estoque,
                        imagem = :imagem,
                        ativo = :ativo
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':categoria_id', (int) $categoriaId, PDO::PARAM_INT);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':preco', $preco);
            $stmt->bindValue(':estoque', (int) $estoque, PDO::PARAM_INT);
            $stmt->bindValue(':imagem', $imagem);
            $stmt->bindValue(':ativo', (int) $ativo, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            header('Location: produtos.php?edicao=sucesso');
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
            <h1>Editar produto</h1>
            <p>Atualize as informações do produto.</p>
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
                        value="<?php echo htmlspecialchars($_POST['nome'] ?? $produto['nome']); ?>"
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
                    ><?php echo htmlspecialchars($_POST['descricao'] ?? $produto['descricao']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoria</label>
                    <select id="categoria_id" name="categoria_id" required>

                        <?php foreach ($categorias as $categoria): ?>

                            <option
                                value="<?php echo (int) $categoria['id']; ?>"
                                <?php echo (($_POST['categoria_id'] ?? $produto['categoria_id']) == $categoria['id']) ? 'selected' : ''; ?>
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
                        value="<?php echo htmlspecialchars($_POST['preco'] ?? $produto['preco']); ?>"
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
                        value="<?php echo htmlspecialchars($_POST['estoque'] ?? $produto['estoque']); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="imagem">Caminho da imagem</label>
                    <input
                        type="text"
                        id="imagem"
                        name="imagem"
                        value="<?php echo htmlspecialchars($_POST['imagem'] ?? $produto['imagem']); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="ativo">Situação do produto</label>
                    <select id="ativo" name="ativo" required>
                        <option value="1" <?php echo (($_POST['ativo'] ?? $produto['ativo']) == '1') ? 'selected' : ''; ?>>
                            Ativo
                        </option>

                        <option value="0" <?php echo (($_POST['ativo'] ?? $produto['ativo']) == '0') ? 'selected' : ''; ?>>
                            Inativo
                        </option>
                    </select>
                </div>

                <button type="submit" class="admin-button">
                    Salvar alterações
                </button>

                <a href="produtos.php" class="admin-back-link">
                    Cancelar
                </a>

            </form>

        </div>

    </section>

</main>

<?php require_once '../includes/footer.php'; ?>