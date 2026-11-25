<?php

session_start();

require_once 'config/database.php';

$produtoId = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);

if (!$produtoId) {
    header('Location: produtos.php');
    exit;
}

$sql = "SELECT id, nome, preco, estoque, imagem
        FROM produtos
        WHERE id = :id AND ativo = 1";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $produtoId, PDO::PARAM_INT);
$stmt->execute();

$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto || $produto['estoque'] < 1) {
    header('Location: produto.php?id=' . $produtoId);
    exit;
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$quantidadeAtual = $_SESSION['carrinho'][$produtoId]['quantidade'] ?? 0;

if ($quantidadeAtual < $produto['estoque']) {

    $_SESSION['carrinho'][$produtoId] = [
        'id' => $produto['id'],
        'nome' => $produto['nome'],
        'preco' => $produto['preco'],
        'imagem' => $produto['imagem'],
        'quantidade' => $quantidadeAtual + 1
    ];

}

header('Location: carrinho.php');
exit;