<?php
session_start();
include 'includes/db.php';
include 'includes/send_email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Captura e sanitiza os dados do formulário
        $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $telefone = htmlspecialchars(trim($_POST['telefone'] ?? ''));
        $data_consulta = trim($_POST['data_consulta'] ?? '');
        $hora_consulta = trim($_POST['hora_consulta'] ?? '');

        // Validação dos campos obrigatórios
        if (empty($nome) || empty($email) || empty($data_consulta) || empty($hora_consulta)) {
            echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        // Validação do e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'E-mail inválido.']);
            exit;
        }

        // Validação da data e hora
        $data_valida = DateTime::createFromFormat('Y-m-d', $data_consulta);
        $hora_valida = DateTime::createFromFormat('H:i', $hora_consulta);

        if (!$data_valida || !$hora_valida) {
            echo json_encode(['status' => 'error', 'message' => 'Data ou hora inválida.']);
            exit;
        }

        // Salvar no banco de dados
        $stmt = $conn->prepare("INSERT INTO agendamentos (nome_cliente, email_cliente, telefone_cliente, data_consulta, hora_consulta) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param("sssss", $nome, $email, $telefone, $data_consulta, $hora_consulta);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        // Enviar e-mail de confirmação
        $assunto = "Confirmacao de Agendamento";
        $mensagem = "Olá $nome,\n\nSua consulta foi agendada para $data_consulta às $hora_consulta.\n\nAtenciosamente,\nEquipe de Atendimento";
        sendEmail($email, $assunto, $mensagem);

        // Retornar uma resposta JSON de sucesso
        echo json_encode('Agendamento realizado com sucesso!');
        header("Location: Listar.php");
    } catch (Exception $e) {
        // Registrar o erro em um log
        error_log("Erro ao processar agendamento: " . $e->getMessage());

        // Retornar uma resposta JSON de erro
        echo json_encode('Ocorreu um erro ao processar o agendamento. Por favor, tente novamente mais tarde.');
    }
}
?>