<?php
class AlunoController {

    public function listar() {
        global $pdo;

        $sql = "SELECT id, nome, matricula FROM usuarios WHERE tipo = 'aluno'";
        $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        Response::json(200, null, $rows);
    }

    public function detalhes($id) {
        global $pdo;

        $sql = "SELECT id, nome, matricula FROM usuarios WHERE id = :id AND tipo = 'aluno'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":id" => $id]);
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$aluno) Response::json(404, "Aluno não encontrado");

        $sqlNotas = "SELECT nota_final, status FROM notas WHERE aluno_id = :id";
        $stmt2 = $pdo->prepare($sqlNotas);
        $stmt2->execute([":id" => $id]);
        $notas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $aluno["notas"] = $notas;

        Response::json(200, null, $aluno);
    }
}
