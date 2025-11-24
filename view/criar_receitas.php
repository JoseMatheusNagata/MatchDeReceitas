<?php require_once __DIR__ . '/../controller/AuthCheckController.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Receita</title>
    <link rel="stylesheet" href="./css/criar_receitas.css">
    <link rel="icon" href="./img/logo.png" type="image/png">
</head>
<body>
    <div id="modal-criar-ingrediente" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="fecharModal()">&times;</span>
            <div id="form-custom" class="form-container" style="box-shadow: none; padding: 0;">
                <form id="form-novo-ingrediente" action="index.php?action=adicionarIngrediente" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <h2>Criar Novo Ingrediente</h2>
                    <div class="form-group">
                        <label>Nome do Ingrediente:</label>
                        <input type="text" name="nome" required placeholder="Ex: Chocolate em pó">
                    </div>
                    <button type="submit" class="btn btn-add">Salvar Ingrediente</button>
                </form>
            </div>
        </div>
    </div>

    <div class="form-container">
        <h2><?= !empty($receita)? 'Editar Receita' : 'Nova Receita' ?></h2>
        <form action="<?= (isset($modo) && $modo === 'editar') ? 'index.php?action=atualizarReceita' : 'index.php?action=adicionarReceita' ?>" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <?php if (isset($modo) && $modo === 'editar' && !empty($receita['id'])): ?>
                <input type="hidden" name="id_receita" value="<?= htmlspecialchars($receita['id']) ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="titulo">Título da Receita:</label>
                <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($receita['titulo'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição (Modo de Preparo):</label>
                <textarea id="descricao" name="descricao"><?= htmlspecialchars($receita['descricao'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="tempo_preparo">Tempo de Preparo:</label>
                <input type="text" id="tempo_preparo" name="tempo_preparo" placeholder="Ex: 45 minutos" value="<?= htmlspecialchars($receita['tempo_preparo'] ?? '') ?>" >
            </div>
            <div class="form-group">
                <label for="id_tipo_receita">Tipo de Receita (Categoria):</label>
                <select id="id_tipo_receita" name="id_tipo_receita" required>
                    <option value="" <?= empty($receita['id_tipo_receita']) ? 'selected' : '' ?>>Selecione uma categoria</option>
                    <?php if (isset($tiposReceita)): ?>
                        <?php foreach ($tiposReceita as $tipo): ?>
                            <option value="<?= htmlspecialchars($tipo['id']) ?>" <?= (isset($receita['id_tipo_receita']) && $receita['id_tipo_receita'] == $tipo['id']) ? 'selected' : '' ?> >
                                <?= htmlspecialchars($tipo['descricao']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="imagem">Foto da Receita:</label>
                <?php if (!empty($receita['imagem'])): ?>
                    <div class="current-image" style="margin-bottom:8px;">
                        <p>Imagem atual:</p>
                        <img src="data:image/jpeg;base64,<?= base64_encode($receita['imagem']) ?>" alt="Imagem da receita" style="max-width:200px; display:block; margin-top:6px;" />
                        <input type="hidden" name="imagem_antiga_present" value="1">
                    </div>
                <?php endif; ?>
                <input type="file" id="imagem" name="imagem" accept="image/*">
            </div>

            <fieldset class="fieldset">
                <legend class="legend">Ingredientes</legend>

                <div class="ingredient-row">
                    <div class="form-group search-container"> <label>Ingrediente</label>
                        <input type="text" id="ingrediente-search" class="ingrediente-search"  
                               placeholder="Digite 2+ letras para buscar..." 
                               onkeyup="buscarIngrediente()" autocomplete="off" > 
                               <div id="ingrediente-search-results"></div>

                        <input type="hidden" id="selected-ingrediente-id">
                        <input type="hidden" id="selected-ingrediente-nome">
                    </div>
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="text" id="quantidade-input" class="quantidade-input" placeholder="Ex: 2 xícaras">
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-add" onclick="adicionarIngredienteNaLista()">Adicionar à Receita</button>
                    <button type="button" class="btn btn-new" onclick="abrirModal()">Novo Ingrediente</button>
                </div>
                <hr>

                <strong>Ingredientes Adicionados:</strong>
                <div id="lista-ingredientes-adicionados" style="margin-top: 15px;"></div>
                <div id="hidden-ingredientes-container">
                    <?php if (!empty($ingredientesDaReceita) && is_array($ingredientesDaReceita)): ?>
                        <?php foreach ($ingredientesDaReceita as $idx => $ing): ?>
                            <?php $uniqueId = 'ing-' . $idx; ?>
                            <div class="item-visual" id="visual-<?= $uniqueId ?>">
                                <span><strong><?= htmlspecialchars($ing['quantidade']) ?></strong> de <?= htmlspecialchars($ing['nome']) ?></span>
                                <button type="button" class="btn btn-remove" onclick="removerIngrediente('<?= $uniqueId ?>', '<?= htmlspecialchars($ing['id']) ?>')">&times;</button>
                            </div>
                            <input type="hidden" name="ingrediente[]" id="hidden-id-<?= $uniqueId ?>" value="<?= htmlspecialchars($ing['id']) ?>">
                            <input type="hidden" name="quantidade[]" id="hidden-qt-<?= $uniqueId ?>" value="<?= htmlspecialchars($ing['quantidade']) ?>">
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </fieldset>

            <br>
            <button type="submit" class="btn submit-btn">Salvar Receita Completa</button>
        </form>
    </div>
    <?php
        include "view/footer.php";
    ?>
</body>
</html>

<script src="js/criar_receitas.js"></script>