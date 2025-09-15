<?php
if (!isset($_SESSION['id'])) {
    header("Location: index.php?action=formLogin&erro=2");
    exit;
}
?>
<h1>Usuario logado com sucesso!</h1>
<h1>Bem-vindo ao Match de Receitas</h1>
<p>Usuário logado: <?= $_SESSION['nome'] ?? 'Sem nome' ?></p>