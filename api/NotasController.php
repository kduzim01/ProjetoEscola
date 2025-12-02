<?php

class NotasController {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        header("Content-Type: application/json");

        $sql = "SELECT id, aluno_id, nota_final, status, data_registro 
                FROM notas 
                ORDER BY data_registro DESC";

        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
    }
}
