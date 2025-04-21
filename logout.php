<?php include('includes/db.php'); ?>
<?php
// Inicia a sessão
session_start();

// Remove todas as variáveis de sessão
$_SESSION = [];

// Destroi a sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finaliza a sessão
session_destroy();

// Redireciona para a página de login
header('Location: login.php');
exit;
?>