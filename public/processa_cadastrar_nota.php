<?php
require_once 'verifica_sessao.php';
require_once 'conexao.php';

$PERFIS_PERMITIDOS = ['administrador', 'professor'];

// Verifica se os dados mínimos foram enviados
if (
    !isset($_POST['aluno_id']) ||
    !isset($_POST['nota']) ||
    !isset($_POST['situacao']) ||
    empty($_POST['aluno_id'])
) {
    $_SESSION['erro'] = "Dados incompletos.";
    header("Location: cadastrar_nota.php");
    exit;
}

$aluno_id   = intval($_POST['aluno_id']);
$nota_final = floatval($_POST['nota']);
$status     = trim($_POST['situacao']);

// Validação adicional
if ($nota_final < 0 || $nota_final > 10) {
    $_SESSION['erro'] = "A nota deve estar entre 0 e 10.";
    header("Location: cadastrar_nota.php");
    exit;
}

if ($status !== "Aprovado" && $status !== "Reprovado") {
    $_SESSION['erro'] = "Situação inválida.";
    header("Location: cadastrar_nota.php");
    exit;
}

// Verifica se o aluno realmente existe e é do tipo aluno
$sqlAluno = "SELECT id FROM usuarios WHERE id = :id AND tipo = 'aluno' LIMIT 1";
$stmt = $pdo->prepare($sqlAluno);
$stmt->execute([':id' => $aluno_id]);
$existe = $stmt->fetch();

if (!$existe) {
    $_SESSION['erro'] = "Aluno inválido.";
    header("Location: cadastrar_nota.php");
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO notas (aluno_id, nota_final, status)
        VALUES (:aluno_id, :nota_final, :status)
    ");

    $stmt->execute([
        ':aluno_id'   => $aluno_id,
        ':nota_final' => $nota_final,
        ':status'     => $status
    ]);

    $_SESSION['msg'] = "Média final registrada com sucesso!";
} catch (Exception $e) {
    $_SESSION['erro'] = "Erro ao registrar nota: " . $e->getMessage();
}

// Redireciona mantendo o padrão
header("Location: cadastrar_nota.php");
exit;
