<?php
include('includes/db.php');


$agendamento = null;


$id = isset($_GET['id']) ? $_GET['id'] : null;
if ($id) {
    $sql = "SELECT * FROM agendamentos WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $agendamento = $result->fetch_assoc();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome_cliente = $_POST['nome_cliente'];
    $email_cliente = $_POST['email_cliente'];
    $telefone_cliente = $_POST['telefone_cliente'];
    $data_consulta = $_POST['data_consulta'];
    $hora_consulta = $_POST['hora_consulta'];
    $status = $_POST['status'];

    $sql = "UPDATE agendamentos SET nome_cliente=?, email_cliente=?, telefone_cliente=?, data_consulta=?, hora_consulta=?, status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $nome_cliente, $email_cliente, $telefone_cliente, $data_consulta, $hora_consulta, $status, $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Agendamento atualizado com sucesso!</div>";
        header('Location: dashboard.php'); 
        exit;
    } else {
        echo "<div class='alert alert-danger'>Erro ao atualizar o agendamento: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Agendamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Editar Agendamento</h1>
    <?php if ($agendamento): ?>
        <form method="POST" action="editar.php">
            <input type="hidden" name="id" value="<?= $agendamento['id'] ?>">
            <div class="mb-3">
                <label for="nome_cliente" class="form-label">Nome do Cliente</label>
                <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" value="<?= $agendamento['nome_cliente'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="email_cliente" class="form-label">Email</label>
                <input type="email" class="form-control" id="email_cliente" name="email_cliente" value="<?= $agendamento['email_cliente'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefone_cliente" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone_cliente" name="telefone_cliente" value="<?= $agendamento['telefone_cliente'] ?>">
            </div>
            <div class="mb-3">
                <label for="data_consulta" class="form-label">Data da Consulta</label>
                <input type="date" class="form-control" id="data_consulta" name="data_consulta" value="<?= $agendamento['data_consulta'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora_consulta" class="form-label">Hora da Consulta</label>
                <input type="time" class="form-control" id="hora_consulta" name="hora_consulta" value="<?= $agendamento['hora_consulta'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="pendente" <?= $agendamento['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="confirmado" <?= $agendamento['status'] === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                    <option value="cancelado" <?= $agendamento['status'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    <?php else: ?>
        <p class="alert alert-warning">Agendamento não encontrado.</p>
    <?php endif; ?>
</div>
</body>
</html>