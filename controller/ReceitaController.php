<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "./model/Receita.php";
require_once "./model/ReceitaDAO.php";
require_once "./model/Ingrediente.php";

class ReceitaController {

    private $dao;
    public function __construct() {
        $this->dao = new ReceitaDAO();
    }

        /** ===========================
     *  PROTEÇÃO CSRF
     *  =========================== */
    private function checkCsrf(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("CSRF token inválido. Ação bloqueada.");
            }
        }
    }

    /** ===========================
     *  salva a receita
     *  =========================== */
     public function adicionarReceita(){
        $this->checkCsrf();
        
        // Verifica se o usuário está logado antes de continuar
        if (!isset($_SESSION['id'])) {
            //Se não estiver logado, redireciona para a página de login com um erro
            header("Location: index.php?action=formLogin&erro=2");
            exit;
        }

        // Processa o upload da imagem
        $imagemBlob = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $caminhoTemporario = $_FILES['imagem']['tmp_name'];
            $imagemBlob = file_get_contents($caminhoTemporario);
        }

        // Cria o objeto Receita com os dados do formulário
        $receita = new Receita(
            null,
            $_SESSION['id'], // Pega o ID do usuário logado na sessão
            $_POST['id_tipo_receita'],
            $_POST['titulo'],
            $_POST['descricao'],
            $imagemBlob,
            $_POST['tempo_preparo'],
            null // A data de criação é gerada automaticamente pelo banco
        );

        // Pega as listas de ingredientes e quantidades

        $ingredientes = $_POST['ingrediente'] ?? [];
        $quantidades = $_POST['quantidade'] ?? [];


        // Chama o DAO para inserir a receita e seus ingredientes
        if($this->dao->inserirReceitaCompleta($receita, $ingredientes, $quantidades)) {
            $_SESSION['alert_message'] = "Receita criada com sucesso!";

        }else {
            $_SESSION['alert_message'] = "Erro ao criar a receita.";
        }
        header("Location: index.php?action=meusSwipes");
        exit();
    }
    


    /** ===========================
     * salva o novo indrediente AGORA COM AJAX
     * =========================== */
    public function adicionarIngrediente() {
        $this->checkCsrf();
        
        //JSON
        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => 'Erro desconhecido.'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome'])) {
            $nome = htmlspecialchars(strip_tags($_POST['nome']));
            $ingrediente = new Ingrediente($nome);
            
            // Chama o DAO que agora retorna os dados
            $novoIngrediente = $this->dao->adicionarIngrediente($ingrediente);

            if ($novoIngrediente) {
                $response = [
                    'success' => true,
                    'message' => 'Ingrediente adicionado!',
                    'ingrediente' => $novoIngrediente
                ];
            } else {
                $response['message'] = 'Erro ao salvar o ingrediente no banco de dados.';
            }
        } else {
            $response['message'] = 'O nome do ingrediente não pode estar vazio.';
        }
        echo json_encode($response);
        exit();
    }

    /**
     * funcao que é chamada ao carregar a tela, carrega os tipos de receitas depois carrega a tela
     */
    public function exibirFormulario() {
        global $pdo;

        // Pede ao Model a lista de tipos de receita
        $tiposReceita = $this->dao->getAllTiposReceitas();
        // Carrega a View e passa os dados para ela
        require __DIR__ . '/../view/criar_receitas.php';
    }

    /**
     * funcao que chama a busca das receitas do usuario
     */
    public function minhasReceitas() {
         $receitas = [];
        if (method_exists($this->dao, 'getAllReceitasByUsuario')) {
            try {
                $ref = new \ReflectionMethod($this->dao, 'getAllReceitasByUsuario');
                if ($ref->getNumberOfRequiredParameters() >= 1) {
                    $receitas = $this->dao->getAllReceitasByUsuario($_SESSION['id']);
                } else {
                    $receitas = $this->dao->getAllReceitasByUsuario();
                }
            } catch (\ReflectionException $e) {
                $receitas = [];
            }
        }

        require __DIR__ . '/../view/minhas_receitas.php';
    }

    /** ===========================
     * Busca ingredientes por nome (AJAX)
     * =========================== */
    public function buscarIngredientesAJAX() {
        header('Content-Type: application/json');
        
        $resultados = [];

        if (isset($_GET['term']) && strlen($_GET['term']) >= 2) {
            $termo = htmlspecialchars(strip_tags($_GET['term']));
            $resultados = $this->dao->buscarIngredientesPorNome($termo);
        }

        echo json_encode($resultados);
        exit();
    }
}
?>