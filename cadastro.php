<?php
session_start();
include('includes/db.php'); // Inclua o arquivo de conexão com o banco de dados

$error = ''; // Variável para mensagens de erro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'] ?? 'user';

    if (empty($username) || empty($password)) {
        $error = "Por favor, preencha todos os campos.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Usuário já cadastrado.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password_hash, $role);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit;
            } else {
                $error = "Erro ao salvar o cadastro. Por favor, tente novamente.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .register-container h2 {
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
        .btn-register {
            background-color: #6f42c1;
            border-color: #6f42c1;
            transition: background-color 0.3s ease;
        }
        .btn-register:hover {
            background-color: #5a31a5;
            border-color: #5a31a5;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Cadastro</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="role">O que você é?</label>
                <select id="role" name="role" class="form-control">
                    <option value="user">Paciente</option>
                    <option value="host">Consultório Médico</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-register">Cadastrar</button>
            <br>
            <div class="login-link">
                <p>Já possui login? <a href="login.php">Faça o login</a></p>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>