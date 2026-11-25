<?php

session_start();

require_once 'config/database.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha) || empty($confirmarSenha)) {

        $erro = 'Preencha todos os campos.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erro = 'Informe um e-mail válido.';

    } elseif (strlen($senha) < 8) {

        $erro = 'A senha deve ter pelo menos 8 caracteres.';

    } elseif ($senha !== $confirmarSenha) {

        $erro = 'As senhas não coincidem.';

    } else {

        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {

            $erro = 'Este e-mail já está cadastrado.';

        } else {

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nome, email, senha_hash)
                    VALUES (:nome, :email, :senha)";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $senhaHash);
            $stmt->execute();

            header('Location: login.php?cadastro=sucesso');
            exit;
        }
    }
}

require_once 'includes/header.php';

?>

<main>

    <section class="auth-page container">

        <div class="auth-card">

            <div class="section-heading">
                <span>FAÇA PARTE DA LEVELUP</span>
                <h1>Criar conta</h1>
                <p>Cadastre-se para aproveitar nossa loja.</p>
            </div>

            <?php if (!empty($erro)): ?>
                <p class="form-error">
                    <?php echo htmlspecialchars($erro); ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="cadastro.php" class="auth-form">

                <div class="form-group">
                    <label for="nome">Nome completo</label>
                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Digite seu nome"
                        value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Digite seu e-mail"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Mínimo de 8 caracteres"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input
                        type="password"
                        id="confirmar_senha"
                        name="confirmar_senha"
                        placeholder="Digite a senha novamente"
                        required
                    >
                </div>

                <button type="submit" class="auth-button">
                    Criar minha conta
                </button>

            </form>

            <p class="auth-alternative">
                Já possui uma conta?
                <a href="login.php">Entrar</a>
            </p>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>