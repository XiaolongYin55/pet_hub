<?php
require_once './jwt.php';

function authenticate($req, $res, $next) {
    $headers = getallheaders();
    if (!isset($headers["Authorization"])) {
        return $res->withStatus(401)->withJson(["error" => "Missing token"]);
    }

    $token = str_replace("Bearer ", "", $headers["Authorization"]);
    $decoded = JwtHandler::validateToken($token);
    
    if (!$decoded) {
        return $res->withStatus(401)->withJson(["error" => "Invalid token"]);
    }

    $req = $req->withAttribute("user", $decoded);
    return $next($req, $res);
}
