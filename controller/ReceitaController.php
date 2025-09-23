<?php
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);
require_once "./model/Receita.php";
require_once "./model/ReceitaDAO.php";
require_once "./model/Ingrediente.php";

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

    public function adicionarIngrediente(){
        if (isset($_GET['action']) && $_GET['action'] == 'adicionarIngrediente') {
        #Verifica se o formulário foi enviado (se a requisição é POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            #Verifica se o campo 'nome' não está vazio
            if (isset($_POST['nome']) && !empty($_POST['nome'])) {
            
                #Atribui o valor de $_POST['nome'] à variável $nome
                $nome = htmlspecialchars(strip_tags($_POST['nome']));

                global $pdo;

                $Ingrediente = new Ingrediente(
                $nome,
                );
                $this->dao->adicionarIngrediente($Ingrediente);
                echo "<h1>Ingrediente salvo com Sucesso!</h1>";
                echo "<p>Nome do Ingrediente: " . $nome . "</p>";    
                } else {
                    echo "O nome do ingrediente não pode ser vazio.";
                }
            }
        }
    }
}
?>