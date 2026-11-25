
<?php

session_start();

// Verifica se o usuário é administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/database.php';

// Recebe o ID do pedido
$pedidoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$pedidoId) {
    header('Location: pedidos.php');
    exit;
}

// Atualiza o status do pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $novoStatus = $_POST['status'] ?? '';

    $statusPermitidos = ['pendente', 'confirmado', 'cancelado'];

    if (in_array($novoStatus, $statusPermitidos, true)) {

        $sql = "UPDATE pedidos
                SET status = :status
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', $novoStatus);
        $stmt->bindValue(':id', $pedidoId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: pedido-detalhes.php?id=$pedidoId");
        exit;
    }
}

// Busca os dados do pedido e do cliente
$sql = "SELECT
            pedidos.id,
            pedidos.data_pedido,
            pedidos.forma_pagamento,
            pedidos.valor_total,
            pedidos.status,
            usuarios.nome AS cliente,
            usuarios.email
        FROM pedidos
        INNER JOIN usuarios
            ON pedidos.usuario_id = usuarios.id
        WHERE pedidos.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $pedidoId, PDO::PARAM_INT);
$stmt->execute();

$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    header('Location: pedidos.php');
    exit;
}

// Busca os produtos do pedido
$sql = "SELECT
            itens_pedido.quantidade,
            itens_pedido.preco_unitario,
            produtos.nome AS produto
        FROM itens_pedido
        INNER JOIN produtos
            ON itens_pedido.produto_id = produtos.id
        WHERE itens_pedido.pedido_id = :pedido_id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':pedido_id', $pedidoId, PDO::PARAM_INT);
$stmt->execute();

$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';

?>

<main>

    <section class="admin-page container">

        <div class="section-heading">
            <span>LEVELUP STORE</span>
            <h1>Detalhes do pedido #<?php echo $pedido['id']; ?></h1>
            <p>Consulte as informações e os produtos desta compra.</p>
        </div>

        <div class="admin-form-container">

            <div class="admin-delete-content">

                <h2>Informações do cliente</h2>

                <p>
                    Cliente:
                    <strong><?php echo htmlspecialchars($pedido['cliente']); ?></strong>
                </p>

                <p>
                    E-mail:
                    <strong><?php echo htmlspecialchars($pedido['email']); ?></strong>
                </p>

                <p>
                    Data:
                    <strong><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></strong>
                </p>

                <p>
                    Pagamento:
                    <strong><?php echo strtoupper($pedido['forma_pagamento']); ?></strong>
                </p>

                <p>
                    Status:
                    <strong><?php echo ucfirst($pedido['status']); ?></strong>
                </p>

                <form method="POST" class="admin-form">

                    <div class="form-group">
                        <label for="status">Alterar status do pedido</label>

                        <select name="status" id="status" required>

                            <option value="pendente"
                                <?php echo $pedido['status'] === 'pendente' ? 'selected' : ''; ?>>
                                Pendente
                            </option>

                            <option value="confirmado"
                                <?php echo $pedido['status'] === 'confirmado' ? 'selected' : ''; ?>>
                                Confirmado
                            </option>

                            <option value="cancelado"
                                <?php echo $pedido['status'] === 'cancelado' ? 'selected' : ''; ?>>
                                Cancelado
                            </option>

                        </select>
                    </div>

                    <button type="submit" class="admin-button">
                        Atualizar status
                    </button>

                </form>

                <h2>Produtos comprados</h2>

                <?php foreach ($itens as $item): ?>

                    <div class="admin-delete-content">

                        <p>
                            Produto:
                            <strong><?php echo htmlspecialchars($item['produto']); ?></strong>
                        </p>

                        <p>
                            Quantidade:
                            <strong><?php echo $item['quantidade']; ?></strong>
                        </p>

                        <p>
                            Preço unitário:
                            <strong>
                                R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?>
                            </strong>
                        </p>

                        <p>
                            Subtotal:
                            <strong>
                                R$ <?php echo number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.'); ?>
                            </strong>
                        </p>

                    </div>

                    <hr>

                <?php endforeach; ?>

                <h2>
                    Total do pedido:
                    R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?>
                </h2>

                <a href="pedidos.php" class="admin-back-link">
                    Voltar para os pedidos
                </a>

            </div>

        </div>

    </section>

</main>

<?php require_once '../includes/footer.php'; ?>
