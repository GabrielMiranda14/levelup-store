<?php

session_start();

require_once 'config/database.php';

// Verifica se o usuário está conectado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verifica se o carrinho não está vazio
$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($carrinho)) {
    header('Location: carrinho.php');
    exit;
}

// Valida a forma de pagamento
$formaPagamento = $_POST['forma_pagamento'] ?? '';

$formasPermitidas = ['pix', 'boleto', 'cartao'];

if (!in_array($formaPagamento, $formasPermitidas, true)) {
    header('Location: checkout.php');
    exit;
}

try {

    // Inicia a transação
    $pdo->beginTransaction();

    $total = 0;
    $produtosValidados = [];

    // Confere os produtos e o estoque diretamente no banco
    foreach ($carrinho as $produtoId => $item) {

        $sql = "SELECT id, nome, preco, estoque
                FROM produtos
                WHERE id = :id AND ativo = 1
                FOR UPDATE";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $produtoId, PDO::PARAM_INT);
        $stmt->execute();

        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            !$produto ||
            $item['quantidade'] < 1 ||
            $item['quantidade'] > $produto['estoque']
        ) {
            throw new Exception('Estoque insuficiente ou produto indisponível.');
        }

        $quantidade = (int) $item['quantidade'];
        $preco = (float) $produto['preco'];

        $subtotal = $preco * $quantidade;
        $total += $subtotal;

        $produtosValidados[] = [
            'id' => $produto['id'],
            'quantidade' => $quantidade,
            'preco' => $preco
        ];
    }

    // Cria o pedido
    $sql = "INSERT INTO pedidos
            (usuario_id, forma_pagamento, valor_total)
            VALUES (:usuario_id, :forma_pagamento, :valor_total)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
    $stmt->bindValue(':forma_pagamento', $formaPagamento);
    $stmt->bindValue(':valor_total', $total);
    $stmt->execute();

    $pedidoId = $pdo->lastInsertId();

    // Registra os itens e atualiza o estoque
    foreach ($produtosValidados as $produto) {

        $sql = "INSERT INTO itens_pedido
                (pedido_id, produto_id, quantidade, preco_unitario)
                VALUES (:pedido_id, :produto_id, :quantidade, :preco_unitario)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':pedido_id', $pedidoId, PDO::PARAM_INT);
        $stmt->bindValue(':produto_id', $produto['id'], PDO::PARAM_INT);
        $stmt->bindValue(':quantidade', $produto['quantidade'], PDO::PARAM_INT);
        $stmt->bindValue(':preco_unitario', $produto['preco']);
        $stmt->execute();

        $sql = "UPDATE produtos
                SET estoque = estoque - :quantidade
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':quantidade', $produto['quantidade'], PDO::PARAM_INT);
        $stmt->bindValue(':id', $produto['id'], PDO::PARAM_INT);
        $stmt->execute();
    }

    // Confirma todas as operações
    $pdo->commit();

    // Limpa o carrinho
    unset($_SESSION['carrinho']);

    // Guarda o número do pedido na sessão
    $_SESSION['ultimo_pedido'] = $pedidoId;

    header('Location: pedido-confirmado.php');
    exit;

} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    die('Não foi possível finalizar o pedido: ' . htmlspecialchars($e->getMessage()));
}