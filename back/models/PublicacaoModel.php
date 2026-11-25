
<?php

class PublicacaoModel {

    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function listar() {

        $sql = "SELECT publicacoes.*, usuarios.nome, usuarios.username
                FROM publicacoes
                INNER JOIN usuarios
                ON publicacoes.id_usuario = usuarios.id_usuario
                ORDER BY publicacoes.id_publicacao DESC";

        $stmt = $this->con->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function buscar($id) {

        $sql = "SELECT publicacoes.*, usuarios.nome, usuarios.username
                FROM publicacoes
                INNER JOIN usuarios
                ON publicacoes.id_usuario = usuarios.id_usuario
                WHERE publicacoes.id_publicacao = ?";

        $stmt = $this->con->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function criar($dados) {

        $sql = "INSERT INTO publicacoes
                (id_usuario, legenda, imagem_url)
                VALUES (?, ?, ?)";

        $stmt = $this->con->prepare($sql);

        return $stmt->execute([
            $dados["id_usuario"],
            $dados["legenda"],
            $dados["imagem_url"] ?? null
        ]);
    }

    public function atualizar($id, $dados) {

        $sql = "UPDATE publicacoes
                SET legenda = ?, imagem_url = ?
                WHERE id_publicacao = ?";

        $stmt = $this->con->prepare($sql);

        return $stmt->execute([
            $dados["legenda"],
            $dados["imagem_url"] ?? null,
            $id
        ]);
    }

    public function excluir($id) {

        $sql = "DELETE FROM publicacoes WHERE id_publicacao = ?";
        $stmt = $this->con->prepare($sql);

        return $stmt->execute([$id]);
    }
}
