<?php
class Auth {

    public static function getUser() {
        session_start();

        if (!isset($_SESSION['usuario_id'])) return null;

        return [
            "id"    => $_SESSION['usuario_id'],
            "nome"  => $_SESSION['nome'],
            "perfil"=> $_SESSION['tipo']
        ];
    }

    public static function requireAuth($perfis = []) {
        $user = self::getUser();

        if (!$user) {
            Response::json(401, "Não autenticado");
        }

        if (!empty($perfis) && !in_array($user['perfil'], $perfis)) {
            Response::json(403, "Sem permissão");
        }

        return $user;
    }
}
