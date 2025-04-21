<?php
include('includes/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM agendamentos WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "Agendamento excluído com sucesso!";
        header('Location: dashboard.php');
    } else {
        echo "Erro ao excluir o agendamento: " . $conn->error;
    }
} else {
    echo "ID não fornecido para exclusão.";
}
?>