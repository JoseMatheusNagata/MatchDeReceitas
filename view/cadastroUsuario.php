<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Novo Usuário</title>
    <link rel="stylesheet" href="./css/cadastro_usuario.css">

</head>
<body>
    
    <main>
        <form method="post" action="index.php?action=salvarUsuario" class="form" enctype="multipart/form-data">
            
            <div class="card">
                <div class="card-top">
                    <img class="imgLogin" src="./img/logo_texto.png" alt="">
                </div>
            
                <h2>Cadastrar Usuário</h2>
                
                
                <div class="card-group">
                    <label>Nome:</label>
                    <input type="text" name="nome" placeholder="Digite seu nome" required>
                </div>
                
                <div class="card-group">
                    <label>E-mail:</label>
                    <input type="text" name="email" placeholder="Digite seu email" required>
                </div> 

                <div class="card-group">
                    <label>Senha:</label>
                    <input type="password" name="senha" placeholder="Digite sua senha" required>
                </div>

                <div class="card-group">
                    <label>Foto de Perfil:</label>
                    <input type="file" name="foto_perfil" accept="image/*">
                </div>

                <div class="card-group btn">
                    <button type="submit">Salvar</button>
                </div>
                <div>
                    <div class="card-group btn">
                    <a href="index.php" class="btn">Voltar à Home</a>
                </div>
            </div>
        </form>
        
    </main>
    <?php
        include "view/footer.php";
    ?>
</body>
</html>