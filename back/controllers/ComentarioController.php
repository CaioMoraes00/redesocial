
<?php

require_once __DIR__ . "/../models/ComentarioModel.php";

class ComentarioController {

    private $model;

    public function __construct($con) {
        $this->model = new ComentarioModel($con);
    }

    public function processar($metodo, $id = null) {

        switch ($metodo) {

            case "GET":

                if (!$id) {
                    http_response_code(400);
                    return ["erro" => "Informe a publicação"];
                }

                return $this->model->listar($id);

            case "POST":

                if (!isset($_SESSION["id_usuario"])) {
                    http_response_code(401);
                    return ["erro" => "Faça login para comentar"];
                }

                $dados = json_decode(file_get_contents("php://input"), true);

                if (!is_array($dados) ||
                    empty($dados["id_publicacao"]) ||
                    empty(trim($dados["comentario"] ?? ""))) {

                    http_response_code(400);
                    return ["erro" => "Informe a publicação e o comentário"];
                }

                $dados["id_usuario"] = $_SESSION["id_usuario"];

                return $this->model->criar($dados);

            case "DELETE":

                if (!isset($_SESSION["id_usuario"])) {
                    http_response_code(401);
                    return ["erro" => "Faça login para excluir"];
                }

                if (!$id) {
                    http_response_code(400);
                    return ["erro" => "Informe o comentário"];
                }

                return $this->model->excluir(
                    $id,
                    $_SESSION["id_usuario"]
                );

            default:

                http_response_code(405);
                return ["erro" => "Método não permitido"];
        }
    }
}
