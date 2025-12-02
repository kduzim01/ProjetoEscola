<?php
class Response {

    public static function json($status, $msg = null, $data = null) {
        http_response_code($status);

        echo json_encode([
            "status" => $status < 300 ? "success" : "error",
            "message" => $msg,
            "data" => $data
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }
}
