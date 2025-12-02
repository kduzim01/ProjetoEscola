<?php
class AuthController {

    public function login() {
        global $pdo;

        // Lê JSON da requisição
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input["matricula"]) || !isset($input["senha"])) {
            Response::json(400, "Dados incompletos. Envie matricula e senha.");
        }

        $matricula = trim($input["matricula"]);
        $senha     = $input["senha"];

        // Busca apenas pelo usuário - NUNCA pela senha
        $sql = "SELECT id, nome, tipo, senha_hash FROM usuarios WHERE matricula = :mat LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":mat" => $matricula]);

        $u = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$u) {
            Response::json(401, "Usuário ou senha incorretos.");
        }

        // Valida senha usando password_verify
        if (!password_verify($senha, $u["senha_hash"])) {
            Response::json(401, "Usuário ou senha incorretos.");
        }

        // Autenticação OK → cria sessão
        session_start();
        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $u['id'];
        $_SESSION['nome']       = $u['nome'];
        $_SESSION['tipo']       = $u['tipo'];

        Response::json(200, "Login realizado com sucesso", [
            "id"   => $u['id'],
            "nome" => $u['nome'],
            "tipo" => $u['tipo']
        ]);
    }

    public function me() {
        $user = Auth::getUser();

        if (!$user) {
            Response::json(401, "Usuário não autenticado");
        }

        Response::json(200, null, $user);
    }
}
