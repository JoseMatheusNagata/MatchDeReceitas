<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "./model/Receita.php";
require_once "./model/ReceitaDAO.php";
require_once "./model/Ingrediente.php";

class ReceitaController {


    public function __construct() {
        $this->dao = new ReceitaDAO();
    }

    #public function minhasReceitas() {
    #    include "view/minhas_receitas.php";
    #}

     public function adicionarReceita(){
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
        $this->dao->inserirReceitaCompleta($receita, $ingredientes, $quantidades);
        
        // Redireciona para a mesma página (ou uma de sucesso)
        #header("Location: index.php?action=minhasReceitas");
        #exit();
    }
    
    
    public function inserirIngredientesDaReceita($id_receita, array $ingredientes, array $quantidades) {
        global $pdo;
        $sql = "INSERT INTO receita_ingrediente (id_receita, id_ingrediente, quantidade) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        foreach ($ingredientes as $index => $id_ingrediente) {
            if (!empty($id_ingrediente) && isset($quantidades[$index])) {
                $stmt->execute([
                    $id_receita,
                    $id_ingrediente,
                    $quantidades[$index]
                ]);
            }
        }
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

    public function exibirFormulario() {
        global $pdo;

        // Pede ao Model a lista de ingredientes
        $ingredientes = $this->dao->getAllIngredientes();
        // Pede ao Model a lista de tipos de receita
        $tiposReceita = $this->dao->getAllTiposReceitas();
        // Carrega a View e passa os dados para ela
        require __DIR__ . '/../view/criar_receitas.php';
    }

}
?>