<?php
require_once 'conexao.php';
session_start();

// Remove token do banco, se existir cookie
if (isset($_COOKIE['auth_token'])) {
    $token = $_COOKIE['auth_token'];
    $tokenHash = hash('sha256', $token);

    $sqlDel = "DELETE FROM tokens_login WHERE token = :token";
    $stmtDel = $pdo->prepare($sqlDel);
    $stmtDel->execute([':token' => $tokenHash]);

    // Apaga cookie do navegador
    setcookie('auth_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
}

// Finaliza sessão PHP
session_unset();
session_destroy();

// Redireciona para a página de login
header('Location: ../index.php');
exit;
?>
