<?php

$rota = $_GET['rota'] ?? '';
$metodo = $_SERVER['REQUEST_METHOD'];

if ($rota === "usuarios") {
    switch ($metodo) {
        case "POST":
            createUsuario($con);
            break;
        case "GET":
            readUsuario($con);
            break;
        case "PUT":
            updateUsuario($con);
            break;
        case "DELETE":
            deleteUsuario($con);
            break;
        default:
            teste();
    }
}