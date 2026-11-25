<?php

require_once __DIR__ . "/../models/CurtidaModel.php";

class CurtidaController {

    private $model;

    public function __construct($con) {
        $this->model = new CurtidaModel($con);
    }

    public function processar($metodo, $id = null) {

        switch ($metodo) {

            case "GET":
                return $this->model->listar();

            case "POST":

                if (!isset($_SESSION["id_usuario"])) {
                    http_response_code(401);

                    return ["erro" => "Faça login para curtir"];
                }

                $dados = json_decode(file_get_contents("php://input"), true);

                if (!is_array($dados) || empty($dados["id_publicacao"])) {
                    http_response_code(400);

                    return ["erro" => "Informe a publicação"];
                }

                $dados["id_usuario"] = $_SESSION["id_usuario"];

                return $this->model->criar($dados);

            case "DELETE":

                if (!isset($_SESSION["id_usuario"])) {
                    http_response_code(401);

                    return ["erro" => "Faça login para remover a curtida"];
                }

                return $this->model->excluir($id, $_SESSION["id_usuario"]);

            default:
                http_response_code(405);

                return ["erro" => "Método não permitido"];
        }
    }
}
