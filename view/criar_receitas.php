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
        <form action="index.php?action=adicionarReceita" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

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
                <input type="text" id="tempo_preparo" name="tempo_preparo" placeholder="Ex: 45 minutos" >
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
                    <div class="form-group search-container"> <label>Ingrediente</label>
                        <input type="text" id="ingrediente-search" class="ingrediente-search" 
                               placeholder="Digite 2+ letras para buscar..." 
                               onkeyup="buscarIngrediente()" autocomplete="off">
                              
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
                <div id="hidden-ingredientes-container"></div>
            </fieldset>

            <br>
            <button type="submit" class="btn submit-btn">Salvar Receita Completa</button>
        </form>
    </div>
</body>
</html>

<script>

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

    let debounceTimer;

    const ingredientesAdicionados = new Set();
    let ingredienteIdCounter = 0;


    // 5. Função de busca AJAX
    function buscarIngrediente() {
        // Limpa o timer anterior
        clearTimeout(debounceTimer);

        const input = document.getElementById('ingrediente-search');
        const resultsContainer = document.getElementById('ingrediente-search-results');
        const term = input.value.trim();

        resultsContainer.innerHTML = '';
        
        // Só busca com 2+ caracteres
        if (term.length < 2) {
            return;
        }

        // Inicia um novo timer (300ms) para só buscar quando o usuário parar de digitar
        debounceTimer = setTimeout(() => {
            fetch('index.php?action=buscarIngredientesAJAX&term=' + encodeURIComponent(term))
                .then(response => response.json())
                .then(matches => {
                    resultsContainer.innerHTML = '';
                    
                    matches.forEach(ing => {
                        const item = document.createElement('div');
                        item.className = 'search-result-item';
                        item.textContent = ing.nome;
                        item.onclick = () => selecionarIngrediente(ing.id, ing.nome);
                        resultsContainer.appendChild(item);
                    });
                })
                .catch(error => {
                    console.error('Erro ao buscar ingredientes:', error);
                    resultsContainer.innerHTML = '<div class="search-result-item">Erro ao buscar.</div>';
                });
        }, 300);
    }

    //funcao de selecionar ingrediente
    function selecionarIngrediente(id, nome) {
        document.getElementById('selected-ingrediente-id').value = id;
        document.getElementById('selected-ingrediente-nome').value = nome;
        document.getElementById('ingrediente-search').value = nome;
        document.getElementById('ingrediente-search-results').innerHTML = '';
    }

    // 7. Função adicionarIngredienteNaLista
    function adicionarIngredienteNaLista() {
        const id = document.getElementById('selected-ingrediente-id').value;
        const nome = document.getElementById('selected-ingrediente-nome').value;
        const quantidade = document.getElementById('quantidade-input').value;

        if (!id || !nome) {
            alert('Por favor, busque e selecione um ingrediente da lista.');
            return;
        }

        if (!quantidade.trim()) {
            alert('Por favor, digite a quantidade.');
            return;
        }

        // Verifica se o ingrediente (pelo ID) já está no Set
        if (ingredientesAdicionados.has(id)) {
            alert('Este ingrediente já foi adicionado.');
            return;
        }

        ingredientesAdicionados.add(id);
        
        const uniqueId = 'ing-' + (ingredienteIdCounter++);

        // adiciona o item visual para o usuário ver
        const listaVisual = document.getElementById('lista-ingredientes-adicionados');
        const itemVisual = document.createElement('div');
        itemVisual.className = 'item-visual';
        itemVisual.id = 'visual-' + uniqueId;
        itemVisual.innerHTML = `
            <span><strong>${quantidade}</strong> de ${nome}</span>
            <button type="button" class="btn btn-remove" onclick="removerIngrediente('${uniqueId}', '${id}')">&times;</button>
        `;
        listaVisual.appendChild(itemVisual);

        // adiciona os inputs hidden que serão enviados com o formulário
        const containerHidden = document.getElementById('hidden-ingredientes-container');
        
        const inputIdHidden = document.createElement('input');
        inputIdHidden.type = 'hidden';
        inputIdHidden.name = 'ingrediente[]'; 
        inputIdHidden.value = id;
        inputIdHidden.id = 'hidden-id-' + uniqueId;
        
        const inputQtHidden = document.createElement('input');
        inputQtHidden.type = 'hidden';
        inputQtHidden.name = 'quantidade[]'; 
        inputQtHidden.value = quantidade;
        inputQtHidden.id = 'hidden-qt-' + uniqueId;

        containerHidden.appendChild(inputIdHidden);
        containerHidden.appendChild(inputQtHidden);

        // limpa os campos de busca e quantidade
        document.getElementById('ingrediente-search').value = '';
        document.getElementById('selected-ingrediente-id').value = '';
        document.getElementById('selected-ingrediente-nome').value = '';
        document.getElementById('quantidade-input').value = '';
    }

    /**
     * Remove um ingrediente da lista (visual e dos inputs hidden)
     * @param {string} uniqueId O ID único do elemento (ex: 'ing-1')
     * @param {string} ingredienteId O ID do ingrediente no banco (ex: '5')
     */
    function removerIngrediente(uniqueId, ingredienteId) {
        document.getElementById('visual-' + uniqueId)?.remove();

        document.getElementById('hidden-id-' + uniqueId)?.remove();
        document.getElementById('hidden-qt-' + uniqueId)?.remove();

        ingredientesAdicionados.delete(ingredienteId);
    }

    // AJAX PARA NOVO INGREDIENTE
    document.getElementById('form-novo-ingrediente').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const form = event.target;
        const formData = new FormData(form);
        const url = form.action;

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.ingrediente) {
                selecionarIngrediente(data.ingrediente.id, data.ingrediente.nome);

                form.querySelector('input[name="nome"]').value = '';
                fecharModal();
                showAjaxNotification(data.message || 'Ingrediente salvo com sucesso!');
            } else {
                showAjaxNotification(data.message || 'Não foi possível adicionar o ingrediente.', 'error');
            }
        })
        .catch(error => {
            console.error('Erro no fetch:', error);
            showAjaxNotification('Ocorreu um erro de comunicação ao salvar.', 'error');
        });
    });
    
    /**
     * Exibe uma notificação flutuante
     * @param {string} message A mensagem para exibir
     * @param {string} type 'success' (verde) ou 'error' (vermelho)
     */
    function showAjaxNotification(message, type = 'success') {
        //Remove qualquer notificação antiga que possa existir
        const oldAlert = document.getElementById('alert-notification');
        if (oldAlert) {
            oldAlert.remove();
        }

        //Cria o novo elemento de notificação
        const alertDiv = document.createElement('div');
        alertDiv.id = 'alert-notification';
        alertDiv.className = 'alert-notification';
        alertDiv.textContent = message;

        //Adiciona cor de sucesso (verde) ou erro (vermelho)
        if (type === 'error') {
            alertDiv.style.backgroundColor = '#d9534f'; 
        } else {
            alertDiv.style.backgroundColor = '#5cb85c'; 
        }

        //Adiciona a notificação ao corpo da página
        document.body.appendChild(alertDiv);

        //quanto tempo fica a notificação na tela
        setTimeout(function() {
            alertDiv.style.opacity = '0';
            setTimeout(function() {
                alertDiv.remove();
            }, 500); 
        }, 4000);
    }

</script>