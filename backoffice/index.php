<?php
    session_start();
    require_once '../inc/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        try {
            // Prepara e executa a consulta para buscar o usuário
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se o usuário existe e se a senha está correta
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: dashboard.php'); // Corrigido para dashboard.php
                exit;
            } else {
                $error = "Usuário ou senha incorretos.";
            }
        } catch (PDOException $e) {
            $error = "Erro ao processar login: " . $e->getMessage();
        }
    }
?>
<?php include_once '../inc/head.php'?>
<body>
    <div class="login">
        <form method="POST">
            <img src="<?php echo $base_url; ?>/assets/images/logo.svg">
            <label>Nome de Usuário</label>
            <input type="text" name="username" class="form-control" required>
            <label>Senha</label>
            <input type="password" name="password" class="form-control" required>
            <button type="submit">Acessar sistema</button>
        </form>
    </div>
    <?php include_once '../inc/bottom.php'; ?>
</body>
</html>