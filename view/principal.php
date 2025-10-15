<?php
require_once __DIR__ . '/../controller/AuthCheckController.php'; 

?>

<h1>Usuario logado com sucesso!</h1>
<h1>Bem-vindo ao Match de Receitas</h1>
<p>Usuário logado: <?= $_SESSION['nome'] ?? 'Sem nome' ?></p>