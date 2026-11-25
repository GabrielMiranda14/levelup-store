<?php

session_start();

require_once 'config/database.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {

        $erro = 'Preencha todos os campos.';

    } else {

        $sql = "SELECT id, nome, email, senha_hash, tipo
                FROM usuarios
                WHERE email = :email";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {

            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            header('Location: index.php');
            exit;

        } else {

            $erro = 'E-mail ou senha incorretos.';
        }
    }
}

require_once 'includes/header.php';

?>

<main>

    <section class="auth-page container">

        <div class="auth-card">

            <div class="section-heading">
                <span>BEM-VINDO DE VOLTA</span>
                <h1>Entrar na conta</h1>
                <p>Acesse sua conta para continuar comprando.</p>
            </div>

            <?php if (!empty($erro)): ?>
                <p class="form-error">
                    <?php echo htmlspecialchars($erro); ?>
                </p>
            <?php endif; ?>

            <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso'): ?>
                <p class="form-success">
                    Conta criada com sucesso! Faça seu login.
                </p>
            <?php endif; ?>

            <form method="POST" action="login.php" class="auth-form">

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
                        placeholder="Digite sua senha"
                        required
                    >
                </div>

                <button type="submit" class="auth-button">
                    Entrar
                </button>

            </form>

            <p class="auth-alternative">
                Ainda não possui uma conta?
                <a href="cadastro.php">Cadastre-se</a>
            </p>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>