<?php

session_start();

$produtoId = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);

if ($produtoId && isset($_SESSION['carrinho'][$produtoId])) {
    unset($_SESSION['carrinho'][$produtoId]);
}

header('Location: carrinho.php');
exit;