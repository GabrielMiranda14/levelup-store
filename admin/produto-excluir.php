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

// Verifica se o produto existe
$sql = "SELECT id, nome FROM produtos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header('Location: produtos.php');
    exit;
}

$erro = '';

// Exclui o produto após a confirmação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $sql = "DELETE FROM produtos WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: produtos.php?exclusao=sucesso');
        exit;

    } catch (PDOException $e) {

        $erro = 'Não foi possível excluir este produto. Ele pode estar associado a um pedido.';

    }
}

require_once '../includes/header.php';

?>

<main>

    <section class="admin-page container">

        <div class="section-heading">
            <span>GERENCIAMENTO</span>
            <h1>Excluir produto</h1>
            <p>Confirme a exclusão do produto selecionado.</p>
        </div>

        <div class="admin-form-container">

            <?php if ($erro !== ''): ?>
                <p class="form-error">
                    <?php echo htmlspecialchars($erro); ?>
                </p>
            <?php endif; ?>

            <div class="admin-delete-content">

                <p>
                    Tem certeza de que deseja excluir o produto
                    <strong>
                        <?php echo htmlspecialchars($produto['nome']); ?>
                    </strong>?
                </p>

                <p>Essa ação não poderá ser desfeita.</p>

                <form method="POST" class="admin-form">

                    <button type="submit" class="admin-delete-button">
                        Confirmar exclusão
                    </button>

                    <a href="produtos.php" class="admin-back-link">
                        Cancelar
                    </a>

                </form>

            </div>

        </div>

    </section>

</main>

<?php require_once '../includes/footer.php'; ?>