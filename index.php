<?php
#inicia a sessao 
#nao remover!!
session_start();

#echo '<pre style="background: #eee; padding: 10px; border: 1px solid #ccc;">';
#var_dump($_SESSION);
#echo '</pre>';
#echo $_POST['nome'];

#echo "<pre>Action = " . ($_GET['action'] ?? 'home') . "</pre>";
require_once "controller/UsuarioController.php";
require_once "controller/ReceitaController.php";

$controllerUsuario = new UsuarioController();
$controllerReceita = new ReceitaController();

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'principal':
        $controllerUsuario->principal();
        break;
    case 'salvarUsuario':
        $controllerUsuario->salvarUsuario();
        break;
    case 'formLogin':
        $controllerUsuario->login();
        break;
    case 'logout':
        $controllerUsuario->logout();
        break;    

    case 'validaLogin':
        $controllerUsuario->validaLogin();
        break;        
    case 'salvarUsuario':
        $controllerUsuario->salvarUsuario();
        break;  
    case 'cadastroUsuario':
        $controllerUsuario->cadastroUsuario();
    break;    
    case 'minhasReceitas':
        #$controllerReceita->minhasReceitas();
        $controllerReceita->exibirFormulario();

        break;
    case 'adicionarIngrediente':
        $controllerReceita->adicionarIngrediente();
        break; 
     
    default:
        $controllerUsuario->home();

}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Match de Receitas</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/index.css">
        </head>
    <body>
        <header>
            <h2 class="logo"><img class="imgLogin" src="./img/tinder.png" alt=""></h2>
            <nav class="navigation">
                <?php if (isset($_SESSION['id'])): ?>
                    <a class="btnNav" href="index.php?action=minhasReceitas">Criar Receitas</a>
                <?php endif; ?>   
                <?php if (isset($_SESSION['id'])): ?>    
                    <a class="btnNav">Meus Matches</a>
                <?php endif; ?>
            </nav>
            
            <?php if (isset($_SESSION['id'])): ?>
                <p>Olá, <?php echo $_SESSION['nome'] ?>!</p>
                <a href="index.php?action=logout" class="btnLogout">Logout</a>
                <?php else : ?>
                    <a href="index.php?action=formLogin" class="btnLogIn">Login</a>
            <?php endif; ?>

            <?php
                if (isset($_SESSION['foto_perfil']) && !empty($_SESSION['foto_perfil'])) {
                    echo '<img class="foto-perfil" src="' . $_SESSION['foto_perfil'] . '" alt="Foto de Perfil">';
                } else {

            }   
            ?>

        </header>


    </body>
</html>