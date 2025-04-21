<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendador de Consultas</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
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
        .btn-voltar {
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agendador de Consultas</h1>

        <?php
        // Consulta SQL para buscar os agendamentos
        $sql = "SELECT * FROM agendamentos ORDER BY data_consulta ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($agendamento = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($agendamento['nome_cliente']) ?></td>
                                <td><?= htmlspecialchars($agendamento['email_cliente']) ?></td>
                                <td><?= date("d/m/Y", strtotime($agendamento['data_consulta'])) ?></td>
                                <td><?= date("H:i", strtotime($agendamento['hora_consulta'])) ?></td>
                                <td><?= htmlspecialchars($agendamento['status']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning no-data">Nenhum agendamento encontrado.</div>
        <?php endif; ?>

        <div class="text-center">
            <a href="add.php" class="btn btn-secondary btn-voltar">Voltar</a>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js e jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>