<?php
require_once 'verifica_sessao.php';
require_once 'conexao.php';

if (!isset($_POST['aluno_id'], $_POST['nota'], $_POST['situacao'])) {
    $_SESSION['erro'] = "Dados incompletos.";
    header("Location: cadastrar_nota.php");
    exit;
}

$aluno_id = intval($_POST['aluno_id']);
$nota_final = floatval($_POST['nota']);
$status = $_POST['situacao'];

try {
    $stmt = $pdo->prepare("
        INSERT INTO notas (aluno_id, nota_final, status)
        VALUES (:aluno_id, :nota_final, :status)
    ");

    $stmt->execute([
        ':aluno_id' => $aluno_id,
        ':nota_final' => $nota_final,
        ':status' => $status
    ]);

    $_SESSION['msg'] = "Média final registrada com sucesso!";
} catch (Exception $e) {
    $_SESSION['erro'] = "Erro ao registrar nota: " . $e->getMessage();
}

header("Location: cadastrar_nota.php");
exit;
