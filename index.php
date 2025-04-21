<?php
session_start();
include('includes/db.php'); 

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura e sanitiza os dados do formulário
    $username = trim($_POST['username']);
    $senha_fornecida = $_POST['password'];

    if (empty($username) || empty($senha_fornecida)) {
        $error = "Por favor, preencha todos os campos.";
    } else {
        // Consulta o banco de dados para encontrar o usuário
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
                // Verifica a senha
                if (password_verify($senha_fornecida, $usuario['password'])) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $usuario['username'];
                    $_SESSION['role'] = $usuario['role']; // Armazena o papel do usuário (host, user, etc.)

                    if ($usuario['role'] === 'host') {
                        // Redirecionar o host para a dashboard
                        header('Location: dashboard.php');
                    } else {
                        // Redirecionar outros usuários
                        header('Location: add.php');
                    }
                    exit;
                } else {
                    $error = "Senha incorreta.";
                }
            } else {
                $error = "Usuário não encontrado.";
            }
        } else {
            $error = "Erro ao acessar o banco de dados. Por favor, tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: 500;
        }
        .btn-login {
            background-color: #6f42c1;
            border-color: #6f42c1;
            transition: background-color 0.3s ease;
        }
        .btn-login:hover {
            background-color: #5a31a5;
            border-color: #5a31a5;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login">Entrar</button>
            <br>
            <div class="register-link">
            <p>Não possui login? <a href="cadastro.php">Faça o cadastro</a></p>
            </div>
        </form>
    </div>
    <!-- Bootstrap JS, Popper.js e jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


