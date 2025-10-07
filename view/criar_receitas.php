<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Receita</title>
    <link rel="stylesheet" href="./css/criar_receitas.css">
</head>
<body>
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
                <button type="button" class="btn btn-add" onclick="adicionarIngredienteNaLista()">Adicionar Ingrediente</button>
                <hr>

                <strong>Ingredientes Adicionados:</strong>
                <div id="lista-ingredientes-adicionados" style="margin-top: 15px;">
                    </div>

                <div id="hidden-ingredientes-container"></div>
            </fieldset>

            <br>
            <button type="submit" class="btn submit-btn">Salvar Receita Completa</button>
        </form>
    </div>

    <div class="form-container">
        <form action="index.php?action=adicionarIngrediente" method="POST">
            <h2>Criar Ingrediente</h2>
            <div class="form-group">
                <label>Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <button type="submit" class="btn btn-add">Criar Ingrediente</button>
        </form>
    </div>
</body>
</html>

<script>
    // Armazena os IDs dos ingredientes que já foram adicionados
    const ingredientesAdicionados = new Set();
    let ingredienteIdCounter = 0;

    function adicionarIngredienteNaLista() {
        const select = document.getElementById('ingrediente-select');
        const inputQuantidade = document.getElementById('quantidade-input');
        
        const idIngrediente = select.value;
        const nomeIngrediente = select.options[select.selectedIndex].text;
        const quantidade = inputQuantidade.value.trim();

        // Validação 1: Não adiciona se não houver ingrediente ou quantidade
        if (!idIngrediente || !quantidade) {
            alert('Por favor, selecione um ingrediente e informe a quantidade.');
            return;
        }

        // Validação 2: Verifica se o ingrediente já foi adicionado
        if (ingredientesAdicionados.has(idIngrediente)) {
            alert('Este ingrediente já foi adicionado à receita.');
            return;
        }

        const uniqueId = ingredienteIdCounter++;

        // Adiciona à lista visual
        const listaVisualContainer = document.getElementById('lista-ingredientes-adicionados');
        const itemVisual = document.createElement('div');
        itemVisual.className = 'item-visual';
        itemVisual.setAttribute('data-id', uniqueId);
        itemVisual.innerHTML = `
            <span>${nomeIngrediente} - ${quantidade}</span>
            <button type="button" class="btn btn-remove" onclick="removerIngrediente(${uniqueId}, '${idIngrediente}')">Remover</button>
        `;
        listaVisualContainer.appendChild(itemVisual);

        // Adiciona os campos ocultos para o formulário
        const hiddenContainer = document.getElementById('hidden-ingredientes-container');
        const hiddenInputs = document.createElement('div');
        hiddenInputs.setAttribute('data-id', uniqueId);
        hiddenInputs.innerHTML = `
            <input type="hidden" name="ingrediente[]" value="${idIngrediente}">
            <input type="hidden" name="quantidade[]" value="${quantidade}">
        `;
        hiddenContainer.appendChild(hiddenInputs);

        // Registra o ID do ingrediente como adicionado
        ingredientesAdicionados.add(idIngrediente);

        // Limpa os campos de entrada
        select.selectedIndex = 0;
        inputQuantidade.value = '';
    }

    function removerIngrediente(uniqueId, idIngrediente) {
        // Remove da lista visual
        const itemVisual = document.querySelector(`.item-visual[data-id="${uniqueId}"]`);
        if (itemVisual) {
            itemVisual.remove();
        }

        // Remove os campos ocultos correspondentes
        const hiddenInputs = document.querySelector(`#hidden-ingredientes-container div[data-id="${uniqueId}"]`);
        if (hiddenInputs) {
            hiddenInputs.remove();
        }

        // Remove o ID do controle de duplicados
        ingredientesAdicionados.delete(idIngrediente);
    }
</script>

<style>
    .item-visual {
        background: #f9f9f9;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>