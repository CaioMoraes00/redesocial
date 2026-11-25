
<?php

class CurtidaModel {

    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function listar() {

        $sql = "SELECT curtidas.*, usuarios.nome, usuarios.username
                FROM curtidas
                INNER JOIN usuarios
                ON curtidas.id_usuario = usuarios.id_usuario";

        $stmt = $this->con->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function criar($dados) {

        $sql = "INSERT INTO curtidas
                (id_usuario, id_publicacao)
                VALUES (?, ?)";

        $stmt = $this->con->prepare($sql);

        return $stmt->execute([
            $dados["id_usuario"],
            $dados["id_publicacao"]
        ]);
    }

    public function excluir($id, $id_usuario) {

        $sql = "DELETE FROM curtidas
                WHERE id_curtida = ? AND id_usuario = ?";

        $stmt = $this->con->prepare($sql);

        return $stmt->execute([
            $id,
            $id_usuario
        ]);
    }
}
