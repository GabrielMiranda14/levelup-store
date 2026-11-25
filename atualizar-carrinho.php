<?php

session_start();

require_once 'config/database.php';

$produtoId = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

if (!$produtoId || !$quantidade || $quantidade < 1) {
    header('Location: carrinho.php');
    exit;
}

$sql = "SELECT estoque FROM produtos
        WHERE id = :id AND ativo = 1";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $produtoId, PDO::PARAM_INT);
$stmt->execute();

$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (
    $produto &&
    isset($_SESSION['carrinho'][$produtoId]) &&
    $quantidade <= $produto['estoque']
) {
    $_SESSION['carrinho'][$produtoId]['quantidade'] = $quantidade;
}

header('Location: carrinho.php');
exit;