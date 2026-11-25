<?php

function inserirUsuario($con, $dados){

    $stmt = $con->prepare(
        "INSERT INTO usuarios (nome, username, email, senha)
         VALUES (?, ?, ?, ?)"
    );

    $senhaHash = password_hash($dados["senha"], PASSWORD_DEFAULT);

    $stmt->execute([
        $dados["nome"],
        $dados["username"],
        $dados["email"],
        $senhaHash
    ]);

    return $con->lastInsertId();
}

function listarUsuario($con){

    $stmt = $con->query(
        "SELECT id_usuario, nome, username, email, data_cadastro
         FROM usuarios"
    );

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function atualizarUsuario($con, $id, $dados){

    $senhaHash = password_hash($dados["senha"], PASSWORD_DEFAULT);

    $stmt = $con->prepare(
        "UPDATE usuarios SET senha = ? WHERE id_usuario = ?"
    );

    $stmt->execute([
        $senhaHash,
        $id
    ]);
}

function buscarUsuarioPorEmail($con, $email){

    $stmt = $con->prepare(
        "SELECT * FROM usuarios WHERE email = ?"
    );

    $stmt->execute([$email]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
