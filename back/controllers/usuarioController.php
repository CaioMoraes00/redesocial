
<?php

require_once __DIR__ . "/../models/usuarioModel.php";

function teste(){

    echo json_encode([
        "mensagem" => "Back-end respondendo com sucesso!"
    ]);

    exit;
}

function receberDadosUsuario(){

    $dados = json_decode(file_get_contents("php://input"), true);

    if(!is_array($dados)){
        http_response_code(400);

        echo json_encode([
            "erro" => "Envie um objeto JSON com os dados do usuario"
        ]);

        return null;
    }

    foreach(["nome", "username", "email", "senha"] as $campo){

        if(!isset($dados[$campo]) ||
           !is_string($dados[$campo]) ||
           trim($dados[$campo]) === ""){

            http_response_code(400);

            echo json_encode([
                "erro" => "O campo $campo e obrigatorio"
            ]);

            return null;
        }
    }

    return $dados;
}

function receberIDUsuario(){

    $id = filter_var(
        $_GET["id"] ?? null,
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1]]
    );

    if($id === false || $id === null){

        http_response_code(400);

        echo json_encode([
            "erro" => "Informe um id de usuario valido"
        ]);

        return null;
    }

    return $id;
}

function createUsuario($con){

    $dados = receberDadosUsuario();

    if($dados === null) return;

    $id = inserirUsuario($con, $dados);

    http_response_code(201);

    echo json_encode([
        "mensagem" => "Usuario criado com sucesso!",
        "id_usuario" => $id
    ]);
}

function readUsuario($con){

    echo json_encode(listarUsuario($con));
}

function updateUsuario($con){

    $id = receberIDUsuario();

    if($id === null) return;

    $dados = receberDadosUsuario();

    if($dados === null) return;

    atualizarUsuario($con, $id, $dados);

    echo json_encode([
        "mensagem" => "Senha atualizada com sucesso!"
    ]);
}

function deleteUsuario($con){

    $id = receberIDUsuario();

    if($id === null) return;

    // Implementaremos a exclusao depois.
}

function loginUsuario($con){

    $dados = json_decode(file_get_contents("php://input"), true);

    if(!is_array($dados) ||
       empty($dados["email"]) ||
       empty($dados["senha"])){

        http_response_code(400);

        echo json_encode([
            "erro" => "Informe email e senha"
        ]);

        return;
    }

    $usuario = buscarUsuarioPorEmail($con, trim($dados["email"]));

    if(!$usuario || !password_verify($dados["senha"], $usuario["senha"])){

        http_response_code(401);

        echo json_encode([
            "erro" => "Email ou senha incorretos"
        ]);

        return;
    }

    session_regenerate_id(true);

    $_SESSION["id_usuario"] = $usuario["id_usuario"];
    $_SESSION["nome"] = $usuario["nome"];
    $_SESSION["username"] = $usuario["username"];
    $_SESSION["email"] = $usuario["email"];

    unset($usuario["senha"]);

    echo json_encode([
        "mensagem" => "Login realizado com sucesso!",
        "usuario" => $usuario
    ]);
}
