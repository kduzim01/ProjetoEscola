<?php
header("Content-Type: application/json");

// carrega conexão
require_once __DIR__ . "/config.php";

require_once __DIR__ . "/Response.php";
require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/AlunoController.php";
require_once __DIR__ . "/NotasController.php";

// rota solicitada
$rota = $_GET['rota'] ?? '';

switch ($rota) {

    case 'login':
        $controller = new AuthController($pdo);
        $controller->login();
        break;

    case 'alunos':
        $controller = new AlunoController($pdo);
        $controller->listar();
        break;

    case 'notas':
        $controller = new NotasController($pdo);
        $controller->listar();
        break;

    default:
        echo json_encode(["erro" => "Rota não encontrada."]);
}
