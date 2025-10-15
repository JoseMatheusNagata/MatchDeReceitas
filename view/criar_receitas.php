<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Receita</title>
    <link rel="stylesheet" href="./css/criar_receitas.css">
</head>
<body>
    <div id="modal-criar-ingrediente" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="fecharModal()">&times;</span>
            <div id="form-custom" class="form-container" style="box-shadow: none; padding: 0;">
                <form action="index.php?action=adicionarIngrediente" method="POST">
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
        <h2>Criar Nova Receita</h2>
        <form action="index.php?action=adicionarReceita" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="titulo">Título da Receita:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição (Modo de Preparo):</label>
                <textarea id="descricao" name="descricao"></textarea>
            </div>
            <div class="form-group">
                <label for="tempo_preparo">Tempo de Preparo:</label>
                <input type="text" id="tempo_preparo" name="tempo_preparo" placeholder="Ex: 45 minutos">
            </div>
            <div class="form-group">
                <label for="id_tipo_receita">Tipo de Receita (Categoria):</label>
                <select id="id_tipo_receita" name="id_tipo_receita" required>
                    <option value="">Selecione uma categoria</option>
                    <?php if (isset($tiposReceita)): ?>
                        <?php foreach ($tiposReceita as $tipo): ?>
                            <option value="<?= htmlspecialchars($tipo['id']) ?>">
                                <?= htmlspecialchars($tipo['descricao']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="imagem">Foto da Receita:</label>
                <input type="file" id="imagem" name="imagem" accept="image/*">
            </div>

            <fieldset class="fieldset">
                <legend class="legend">Ingredientes</legend>

                <div class="ingredient-row">
                    <div class="form-group">
                        <label>Ingrediente</label>
                        <select id="ingrediente-select" class="ingrediente-select">
                            <option value="">Selecione um ingrediente</option>
                            <?php if (isset($ingredientes)): ?>
                                <?php foreach ($ingredientes as $ingrediente): ?>
                                    <option value="<?= htmlspecialchars($ingrediente['id']) ?>">
                                        <?= htmlspecialchars($ingrediente['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
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
                <div id="hidden-ingredientes-container"></div>
            </fieldset>

            <br>
            <button type="submit" class="btn submit-btn">Salvar Receita Completa</button>
        </form>
    </div>
</body>
</html>

<script>
    const ingredientesAdicionados = new Set();
    let ingredienteIdCounter = 0;

    // Funções do Modal
    const modal = document.getElementById('modal-criar-ingrediente');
    function abrirModal() {
        modal.style.display = "block";
    }
    function fecharModal() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            fecharModal();
        }
    }

    // Funções para adicionar/remover ingredientes
    function adicionarIngredienteNaLista() {
        const select = document.getElementById('ingrediente-select');
        const inputQuantidade = document.getElementById('quantidade-input');
        const idIngrediente = select.value;
        const nomeIngrediente = select.options[select.selectedIndex].text;
        const quantidade = inputQuantidade.value.trim();

        if (!idIngrediente || !quantidade) {
            alert('Por favor, selecione um ingrediente e informe a quantidade.');
            return;
        }
        if (ingredientesAdicionados.has(idIngrediente)) {
            alert('Este ingrediente já foi adicionado à receita.');
            return;
        }

        const uniqueId = ingredienteIdCounter++;
        const listaVisualContainer = document.getElementById('lista-ingredientes-adicionados');
        const itemVisual = document.createElement('div');
        itemVisual.className = 'item-visual';
        itemVisual.setAttribute('data-id', uniqueId);
        itemVisual.innerHTML = `<span>${nomeIngrediente} - ${quantidade}</span><button type="button" class="btn btn-remove" onclick="removerIngrediente(${uniqueId}, '${idIngrediente}')">Remover</button>`;
        listaVisualContainer.appendChild(itemVisual);

        const hiddenContainer = document.getElementById('hidden-ingredientes-container');
        const hiddenInputs = document.createElement('div');
        hiddenInputs.setAttribute('data-id', uniqueId);
        hiddenInputs.innerHTML = `<input type="hidden" name="ingrediente[]" value="${idIngrediente}"><input type="hidden" name="quantidade[]" value="${quantidade}">`;
        hiddenContainer.appendChild(hiddenInputs);

        ingredientesAdicionados.add(idIngrediente);
        select.selectedIndex = 0;
        inputQuantidade.value = '';
    }

    function removerIngrediente(uniqueId, idIngrediente) {
        const itemVisual = document.querySelector(`.item-visual[data-id="${uniqueId}"]`);
        if (itemVisual) itemVisual.remove();
        const hiddenInputs = document.querySelector(`#hidden-ingredientes-container div[data-id="${uniqueId}"]`);
        if (hiddenInputs) hiddenInputs.remove();
        ingredientesAdicionados.delete(idIngrediente);
    }
</script>