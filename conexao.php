<?php
// Ajuste as credenciais conforme seu ambiente
$db_host = '127.0.0.1';
$db_name = 'escola';
$db_user = 'root';
$db_pass = ''; // defina a senha do seu MySQL

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Exceptions para facilitar debug seguro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // usar prepares nativos do MySQL
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // Em produção, registre o erro num log; evite expor detalhes ao usuário
    http_response_code(500);
    echo "Erro ao conectar ao banco de dados.";
    exit;
}