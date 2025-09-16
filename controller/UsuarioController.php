<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "./model/Usuario.php";
require_once "./model/UsuarioDAO.php";

class UsuarioController {


    public function __construct() {
        $this->dao = new UsuarioDAO();
    }

    public function home() {
        include "view/home.php";
    }

    public function login(){
        include "view/login.php";
    }

    public function principal() {
        include "view/principal.php";
    }

    public function cadastroUsuario(){
        include "view/cadastroUsuario.php";
    }

    public function logout(){
        session_start();

        $_SESSION = array();

        $params = session_get_cookie_params();

        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );

        session_destroy();

        header("Location: index.php");
        exit();
    }

    public function validaLogin(){    
        if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])){
            $email = addslashes($_POST['email']);
            $senha = addslashes($_POST['senha']);

            if($this->dao->login($email, $senha) === true && isset($_SESSION['id'])){
                header("Location: index.php?action=principal");
                exit;
            } else {
                header("Location: index.php?action=login&erro=1");
                exit;
            }
        }else{
            header("Location: index.php?action=login.php");
        }
    }

    public function salvarUsuario() {
        global $pdo;
        
        $fotoBlob = null; // Inicia a variável da foto como nula

        // Verifica se um arquivo foi enviado e se não houve erros
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            // Pega o caminho temporário do arquivo
            $caminhoTemporario = $_FILES['foto_perfil']['tmp_name'];
            // Lê o conteúdo binário do arquivo para salvar como BLOB
            $fotoBlob = file_get_contents($caminhoTemporario);
        }

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        
        // CRIPTOGRAFA A SENHA ANTES DE SALVAR
        $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $Usuario = new Usuario(
            null,
            $nome,
            $email,
            $senhaHash, // Salva a senha criptografada
            $fotoBlob // Agora com o conteúdo correto da imagem (ou null)
        );

        $this->dao->inserir($Usuario);
        header("Location: index.php?action=home");
    }

   

    public function authLogin($email, $senha) {
        $usuario = $this->dao->buscarPorEmail($email);

        if ($usuario) {
            if (password_verify($senha, $usuario->senha)) {
                session_start();
                $_SESSION['id'] = $usuario->id;
                $_SESSION['nome'] = $usuario->nome;

                if (!empty($usuario->foto_perfil)) {
                    $_SESSION['foto_perfil'] = 'data:image/jpeg;base64,' . base64_encode($usuario->foto_perfil);
                } else {
                    $_SESSION['foto_perfil'] = null;
                }
                echo "logado";
                return true; // login bem-sucedido
            } else {
                return "Senha incorreta!";
            }
        } else {
            return "Usuário não encontrado!";
        }
    }

}


?>