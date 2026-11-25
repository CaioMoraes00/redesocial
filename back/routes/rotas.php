
<?php

require_once __DIR__ . "/../controllers/usuarioController.php";
require_once __DIR__ . "/../controllers/PublicacaoController.php";
require_once __DIR__ . "/../controllers/CurtidaController.php";
require_once __DIR__ . "/../controllers/ComentarioController.php";

$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];
$id = $_GET["id"] ?? null;

// Verifica requisicoes OPTIONS
if ($metodo === "OPTIONS") {
    http_response_code(204);
    exit;
}

// ROTA USUARIO
if ($rota === "usuario") {

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
            http_response_code(405);
            echo json_encode([
                "erro" => "Metodo nao permitido"
            ]);
    }

// ROTA LOGIN
} elseif ($rota === "login") {

    if ($metodo === "POST") {
        loginUsuario($con);
    } else {
        http_response_code(405);
        echo json_encode([
            "erro" => "Metodo nao permitido"
        ]);
    }

// ROTA PUBLICACAO
} elseif ($rota === "publicacao") {

    $controller = new PublicacaoController($con);
    $resultado = $controller->processar($metodo, $id);

    echo json_encode($resultado);

// ROTA CURTIDA
} elseif ($rota === "curtida") {

    $controller = new CurtidaController($con);
    $resultado = $controller->processar($metodo, $id);

    echo json_encode($resultado);

// ROTA COMENTARIO
} elseif ($rota === "comentario") {

    $controller = new ComentarioController($con);
    $resultado = $controller->processar($metodo, $id);

    echo json_encode($resultado);

// ROTA NAO ENCONTRADA
} else {

    http_response_code(404);

    echo json_encode([
        "erro" => "Rota nao encontrada"
    ]);
}


