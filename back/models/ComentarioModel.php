
<?php

class ComentarioModel {

    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function listar($id_publicacao) {

        $sql = "SELECT comentarios.*, usuarios.nome, usuarios.username
                FROM comentarios
                INNER JOIN usuarios
                ON comentarios.id_usuario = usuarios.id_usuario
                WHERE comentarios.id_publicacao = ?
                ORDER BY comentarios.id_comentario DESC";

        $stmt = $this->con->prepare($sql);
        $stmt->execute([$id_publicacao]);

        return $stmt->fetchAll();
    }

    public function criar($dados) {

        $sql = "INSERT INTO comentarios
                (id_usuario, id_publicacao, comentario)
                VALUES (?, ?, ?)";

        $stmt = $this->con->prepare($sql);

        return $stmt->execute([
            $dados["id_usuario"],
            $dados["id_publicacao"],
            $dados["comentario"]
        ]);
    }

    public function excluir($id, $id_usuario) {

        $sql = "DELETE FROM comentarios
                WHERE id_comentario = ? AND id_usuario = ?";

        $stmt = $this->con->prepare($sql);

        return $stmt->execute([
            $id,
            $id_usuario
        ]);
    }
}
