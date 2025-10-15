<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
  
    <form class="form" action="index.php?action=validaLogin" method="POST">
        <div class="card">
            <div class="card-top">
                <img class="imgLogin" src="./img/tinder.png" alt="">
                <h2 class="title">Match de Receitas</h2>
                <p>Receitas na palma da sua mão!</p>
            </div>

            <?php
                if (isset($_GET['erro'])) {
                    if ($_GET['erro'] == 1) {
                        echo '<p style="color: red; text-align: center;">E-mail ou senha inválidos!</p>';
                    } elseif ($_GET['erro'] == 2) {
                        echo '<p style="color: red; text-align: center;">Acesso restrito. Faça login para continuar.</p>';
                    }
                }
            ?>

            <div class="card-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Digite seu Email" required>
                
            </div>
            <div class="card-group">
                <label>Senha</label>
                <input type="password" name="senha" placeholder="Digite sua senha" required>
                
            </div>

            <div class="card-group">
                <label><input type="checkbox"> Lembre-me</label>
                
            </div>

            <div class="card-group btn">
                <button type="submit">Acessar</button>
            </div>

            <div class="card-group">
                <a href="index.php?action=cadastroUsuario">Não é Cadastrado? Clique aqui.</a>
            </div>
        </div>
    </form>
</body>
</html>
