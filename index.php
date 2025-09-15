<?php
#inicia a sessao 
#nao remover!!
session_start();

echo "<pre>Action = " . ($_GET['action'] ?? 'home') . "</pre>";
require_once "controller/UsuarioController.php";

$controllerUsuario = new UsuarioController();


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
    case 'validaLogin':
        $controllerUsuario->validaLogin();
        break;        
    case 'salvarUsuario':
        $controllerUsuario->salvarUsuario();
        break;  
    case 'cadastroUsuario':
        $controllerUsuario->cadastroUsuario();
    break;    
    default:
        $controllerUsuario->home();

}
?>