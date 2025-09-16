<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "./model/Receita.php";
require_once "./model/ReceitaDAO.php";

class ReceitaController {


    public function __construct() {
        $this->dao = new ReceitaDAO();
    }

    public function minhasReceitas() {
        include "view/minhas_receitas.php";
    }


    public function adicionarReceita(){
        global $pdo;

        $Receita = new Receita(
            null,
            $id_tipo_receita,
            $titulo,
            $descricao,
            $imagem,
            $tempo_preparo,
            $tempo_criacao,
        );
        $this->dao->inserir($Receita);


    }



}
?>