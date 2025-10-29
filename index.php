<?php
#inicia a sessao 
#nao remover!!
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

#echo '<pre style="background: #eee; padding: 10px; border: 1px solid #ccc;">';
#var_dump($_SESSION);
#echo '</pre>';
#echo $_POST['nome'];

#echo "<pre>Action = " . ($_GET['action'] ?? 'home') . "</pre>";
require_once "controller/UsuarioController.php";
require_once "controller/ReceitaController.php";
require_once "controller/SwipeController.php";
require_once "controller/GeladeiraController.php";


$controllerUsuario = new UsuarioController();
$controllerReceita = new ReceitaController();
$controllerSwipe = new SwipeController();
$controllerGeladeira = new GeladeiraController();

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'principal':
        $controllerUsuario->principal();
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

    case 'criarReceitas':
        $controllerReceita->exibirFormulario();
        break;

    case 'adicionarIngrediente':
        $controllerReceita->adicionarIngrediente();
        break;

    case 'adicionarReceita':
        $controllerReceita->adicionarReceita();
        break;

    case 'meusSwipes':
        $controllerSwipe->meusSwipes();
        break;  

    case 'alterarStatusSwipe': 
        $controllerSwipe->alterarStatusSwipe();
        break;
    case 'carregarReceitas':
        $controllerSwipe->carregarReceitas();
        break;
    
    case 'minhasReceitas':
        $controllerReceita->minhasReceitas();
        break;

    case 'carregarFeed':
        $controllerSwipe->carregarFeed();
        break;

    case 'carregarMinhaGeladeira':
        $controllerGeladeira->carregarMinhaGeladeira();
        break;
        
    case 'adicionarIngredienteGeladeira':
        $controllerGeladeira->adicionarIngredienteGeladeira();
        break;
    
    case 'removerIngredienteGeladeira':
        $controllerGeladeira->removerIngredienteGeladeira();
        break;
    
      case 'salvarSwipe':
        $controllerSwipe->salvarSwipe();
        break;
    
    case 'buscarIngredientesAJAX':
        $controllerReceita->buscarIngredientesAJAX();
        break;

    default:
        $controllerUsuario->home();
        break;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Match de Receitas</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/index.css">
        <link rel="icon" href="./img/logo1.png" type="image/png">
        </head>
    <body>
        <header>
            <h2 class="logo"><img class="imgLogin" src="./img/logo1.png" alt=""></h2>
            <nav class="navigation">
                <?php if (isset($_SESSION['id'])): ?>
                    <a class="btnNav" href="index.php?action=criarReceitas">Criar Receitas</a>
                <?php endif; ?>   
       
                <?php if (isset($_SESSION['id'])): ?>
                    <a class="btnNav" href="index.php?action=meusSwipes">Meus Swipes</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['id'])): ?>
                    <a class="btnNav" href="index.php?action=carregarFeed">Feed</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['id'])): ?>
                    <a class="btnNav" href="index.php?action=minhasReceitas">Minhas Receitas</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['id'])): ?>
                    <a class="btnNav" href="index.php?action=carregarMinhaGeladeira">Minha Geladeira</a>
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

            <?php if (isset($_SESSION['alert_message'])): ?>
                <div id="alert-notification" class="alert-notification">
                    <?php echo $_SESSION['alert_message']; ?>
                </div>
                <?php unset($_SESSION['alert_message']); // Limpa a mensagem para não exibir novamente ?>
                <script>
                    // Faz a notificação desaparecer após 5 segundos
                    setTimeout(function() {
                        const alert = document.getElementById('alert-notification');
                        if (alert) {
                            // Adiciona uma transição suave para desaparecer
                            alert.style.opacity = '0';
                            setTimeout(() => alert.style.display = 'none', 500);
                        }
                    }, 5000);
                </script>
            <?php endif; ?>

        </header>
    </body>
</html>