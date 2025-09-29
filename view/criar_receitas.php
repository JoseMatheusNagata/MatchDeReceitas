<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Receita</title>
    <link rel="stylesheet" href="./css/minhas_receitas.css">
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
                <div id="ingredientes-container">
                    <div class="ingredient-row">
                        <div class="form-group">
                            <label>Ingrediente</label>
                            <select name="ingrediente[]" class="ingrediente-select" required>
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
                            <input type="text" name="quantidade[]" class="quantidade-input" placeholder="Ex: 2 xícaras" required>
                        </div>
                    </div>
                </div>
                    <button type="button" class="btn btn-add" onclick="adicionarNovaLinha()">Adicionar mais ingredientes</button>
                <hr>

                <strong>Ingredientes Adicionados na Receita:</strong>
                <div id="lista-ingredientes-adicionados" style="margin-top: 15px;">
                </div>
            </fieldset>

            <br>
            <button type="submit" class="btn submit-btn">Salvar Receita Completa</button>
        </form>
        
    </div>
    <div class="form-container">
        <form action="index.php?action=adicionarIngrediente" method="POST">
            <h2>Criar Ingrediente</h2>

            <div class="form-group">
                <label >Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <button type="submit" class="btn btn-add" >Criar Ingrediente</button>
  
        </form>
    </div>


</body>
</html>

<script>
// Adiciona um "ouvinte" de eventos no container principal dos ingredientes.
        // Isso é mais eficiente do que adicionar um ouvinte para cada campo individualmente.
        document.getElementById('ingredientes-container').addEventListener('change', atualizarListaVisual);
        document.getElementById('ingredientes-container').addEventListener('input', atualizarListaVisual);

        function adicionarNovaLinha() {
            const container = document.getElementById('ingredientes-container');
            // Clona a primeira linha de ingrediente que serve como modelo
            const novaLinha = container.querySelector('.ingredient-row').cloneNode(true);

            // Limpa os valores dos campos clonados para que o usuário possa preencher
            novaLinha.querySelector('select').selectedIndex = 0;
            novaLinha.querySelector('input').value = '';

            // Cria um botão de remover para a nova linha
            const btnRemover = document.createElement('button');
            btnRemover.type = 'button';
            btnRemover.className = 'btn btn-remove';
            btnRemover.innerText = 'Remover';
            btnRemover.onclick = function() {
                // Remove o elemento pai do botão (a div.ingredient-row)
                this.parentElement.remove();
                // Atualiza a lista visual após remover a linha
                atualizarListaVisual();
            };
            
            novaLinha.appendChild(btnRemover);
            container.appendChild(novaLinha);
        }

        function atualizarListaVisual() {
            const listaContainer = document.getElementById('lista-ingredientes-adicionados');
            // Limpa a lista atual para recriá-la com os valores mais recentes
            listaContainer.innerHTML = '';

            // Pega todas as linhas de ingredientes que existem no formulário
            const todasAsLinhas = document.querySelectorAll('#ingredientes-container .ingredient-row');

            todasAsLinhas.forEach(linha => {
                const select = linha.querySelector('.ingrediente-select');
                const inputQuantidade = linha.querySelector('.quantidade-input');
                
                // Pega o texto do ingrediente selecionado (ex: "Farinha de Trigo")
                const nomeIngrediente = select.options[select.selectedIndex].text;
                // Pega o valor digitado da quantidade
                const quantidade = inputQuantidade.value;

                // Só adiciona na lista se um ingrediente foi selecionado e uma quantidade foi digitada
                if (select.value && quantidade) {
                    const itemDaLista = document.createElement('div');
                    itemDaLista.textContent = `${nomeIngrediente} - ${quantidade}`; // Ex: "Farinha de Trigo - 2 xícaras"
                    listaContainer.appendChild(itemDaLista);
                }
            });
        }

</script>