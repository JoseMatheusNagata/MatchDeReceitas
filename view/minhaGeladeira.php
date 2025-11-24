<?php require_once __DIR__ . '/../controller/AuthCheckController.php'; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minha Geladeira</title>
    <link rel="stylesheet" href="./css/minha_geladeira.css">
</head>
<body>
    
    <div class="container">
        <h1><img src="./img/logo.png" style="width:50px; vertical-align: middle;" alt=""> Minha Geladeira</h1>
        <p style="text-align: center; margin-top: -20px; margin-bottom: 30px;">Adicione os ingredientes que você tem em casa e veja quais receitas pode preparar!</p>

        <div class="geladeira-gerenciar">
            
            <div class="geladeira-add">
                <form id="form-add-ingrediente" action="index.php?action=adicionarIngredienteGeladeira" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                    <h3>Adicionar à Geladeira</h3>
                    <div class="form-group search-container">
                        <label for="ingrediente-search">Selecione o Ingrediente:</label>
                        <input type="text" id="ingrediente-search" 
                            placeholder="Digite 2+ letras para buscar..." 
                            onkeyup="buscarIngrediente()" 
                            autocomplete="off">
                        <div id="ingrediente-search-results"></div>
                        <input type="hidden" id="selected-ingrediente-id" name="id_ingrediente">
                    </div>
                    <button type="submit" class="btn btn-add">Adicionar Ingrediente</button>
                </form> 
            </div>

            <div class="geladeira-lista">
                <h3>Ingredientes na sua Geladeira</h3>
                <div id="lista-geladeira-atual">
                    <?php if (!empty($minhaGeladeira)): ?>
                        <?php foreach ($minhaGeladeira as $ing): ?>
                            <div class="item-visual" data-id="<?= htmlspecialchars($ing['id']) ?>">
                                <span><?= htmlspecialchars($ing['nome']) ?></span>
                                <button type="button" class="btn-remove" onclick="removerIngrediente(<?= htmlspecialchars($ing['id']) ?>)">&times;</button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p id="msg-geladeira-vazia">Sua geladeira está vazia.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="receitas-recomendadas">
            <h2>Receitas que você pode fazer agora!</h2>
            
            <div id="receitas-recomendadas-content">
                <?php
                    include "view/listaReceitasRecomendadas.php";
                ?>
            </div>

        </div>
    </div>

<script src="js/minha_geladeira.js"></script>

<?php
    include "view/footer.php";
?>
</body>
</html>