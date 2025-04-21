<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'host') {
    header('Location: index.php');
    exit;
}

// Consulta SQL para buscar os agendamentos
$sql = "SELECT * FROM agendamentos ORDER BY data_consulta ASC";
$result = $conn->query($sql);
$agendamentos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .no-data {
            text-align: center;
            color: #6c757d;
        }
        .btn-actions {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Painel Administrativo</h1>
        <?php if (empty($agendamentos)): ?>
            <div class="alert alert-info no-data">Nenhum agendamento encontrado.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $agendamento): ?>
                            <tr>
                                <td><?= htmlspecialchars($agendamento['nome_cliente']) ?></td>
                                <td><?= htmlspecialchars($agendamento['email_cliente']) ?></td>
                                <td><?= date("d/m/Y", strtotime($agendamento['data_consulta'])) ?></td>
                                <td><?= date("H:i", strtotime($agendamento['hora_consulta'])) ?></td>
                                <td><?= htmlspecialchars($agendamento['status']) ?></td>
                                <td>
                                    <div class="btn-actions">
                                        <a href="editar.php?id=<?= $agendamento['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="excluir.php?id=<?= $agendamento['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este agendamento?')">Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="text-center mt-3">
            <a href="add.php" class="btn btn-primary">Agendar</a>
            <a href="logout.php" class="btn btn-secondary">Sair</a>
        </div>
    </div>
    <!-- Bootstrap JS, Popper.js e jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>