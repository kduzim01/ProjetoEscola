<?php
// Configurações do banco
$db_host = 'localhost';
$db_name = 'escola';  // nome correto do seu banco
$db_user = 'root';
$db_pass = ''; // deixe vazio mesmo, como no seu XAMPP

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erro ao conectar ao banco: " . $e->getMessage();
    exit;
}
