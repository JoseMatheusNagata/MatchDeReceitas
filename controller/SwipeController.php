<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "./model/SwipeDAO.php"; 
require_once "./model/Swipe.php";
require_once "./model/ReceitaDAO.php";

class SwipeController {

    /**
     * Carrega os swipes do usuario logado
     */
    public function meusSwipes() {
        if (!isset($_SESSION['id'])) {
            header("Location: index.php?action=formLogin&erro=2");
            exit;
        }

        $swipeDAO = new SwipeDAO();
        $receitaDAO = new ReceitaDAO();

        $status_filtro = $_GET['status'] ?? 'like';

        $tipo_receita_filtro = $_GET['id_tipo_receita'] ?? null;

        $swipes = $swipeDAO->getSwipesByUsuario($_SESSION['id'], $status_filtro, $tipo_receita_filtro);

        $tiposReceita = $receitaDAO->getAllTiposReceitas();

        include "view/meus_swipes.php";
    }



    /**
     * Altera o status de um swipe de like para dislike ou vice-versa.
     */
    public function alterarStatusSwipe() {
        if (!isset($_SESSION['id'])) {
            header("Location: index.php?action=formLogin&erro=2");
            exit;
        }

        if (isset($_POST['id_receita']) && isset($_POST['status_atual'])) {
            $id_receita = $_POST['id_receita'];
            $status_atual = $_POST['status_atual'];
            $id_usuario = $_SESSION['id'];

            $novo_status = ($status_atual == 'like') ? 'dislike' : 'like';

            $swipeDAO = new SwipeDAO();
            if ($swipeDAO->mudarStatus($id_usuario, $id_receita, $novo_status)) {
                if($_POST['status_atual'] == 'like'){
                    $_SESSION['alert_message'] = "Receita movida para as receitas 'Não Curtidas'!";
                }else {
                    $_SESSION['alert_message'] = "Receita movida para as receitas 'Curtidas'!";
                }
            } else {
                $_SESSION['alert_message'] = "Erro ao tentar alterar o status da receita.";
            }        
        }

        header("Location: index.php?action=meusSwipes");
        exit;
    }

}
?>