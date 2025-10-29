<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "./model/Geladeira.php";
require_once "./model/GeladeiraDAO.php";
require_once "./model/ReceitaDAO.php";
#error_log("ID do usuário na sessão: " . $_SESSION['id']);

class GeladeiraController {

    private $dao;
    public function __construct() {
        $this->dao = new GeladeiraDAO();
    }

    /** ===========================
     * PROTEÇÃO CSRF
     * =========================== */
    private function checkCsrf(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Token CSRF inválido.']);
                exit;
            }
        }
    }

    /**
     * carrega a pagina minha geladeira buscando:
     * -todos os ingredientes 
     * -os ingredientes que o usuário já tem
     * -as receitas recomendadas com base no que ele tem
     */
    public function carregarMinhaGeladeira() {
        if (!isset($_SESSION['id'])) {
            header("Location: index.php?action=formLogin&erro=2");
            exit;
        }
        
        $id_usuario = $_SESSION['id'];

        
        //busca os ingredientes que o usuario tem na geladeira
        $minhaGeladeira = $this->dao->getIngredientesByUsuario($id_usuario);

        //busca as receitas compativeis
        $receitasRecomendadas = $this->dao->buscarReceitasCompativeis($id_usuario);

        //carrega a view
        include "view/minhaGeladeira.php";
    }    

    /**
     * ajax para adicionar ingrediente na geladeira
     */
    public function adicionarIngredienteGeladeira() {
        $this->checkCsrf();
        header('Content-Type: application/json');

        if (!isset($_SESSION['id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
            exit;
        }

        if (isset($_POST['id_ingrediente'])) {
            $id_usuario = $_SESSION['id'];
            $id_ingrediente = $_POST['id_ingrediente'];
            
            if ($this->dao->adicionarIngrediente($id_usuario, $id_ingrediente)) {
                echo json_encode(['success' => true, 'message' => 'Ingrediente adicionado!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao adicionar.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum ingrediente selecionado.']);
        }
        exit;
    }

    /**
     * ajax para deletar o ingrediente da geladeira
     */
    public function removerIngredienteGeladeira() {
        $this->checkCsrf();
        header('Content-Type: application/json');

        if (!isset($_SESSION['id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
            exit;
        }

        if (isset($_POST['id_ingrediente'])) {
            $id_usuario = $_SESSION['id'];
            $id_ingrediente = $_POST['id_ingrediente'];
            
            if ($this->dao->removerIngrediente($id_usuario, $id_ingrediente)) {
                echo json_encode(['success' => true, 'message' => 'Ingrediente removido!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao remover.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum ingrediente selecionado.']);
        }
        exit;
    }

    
}