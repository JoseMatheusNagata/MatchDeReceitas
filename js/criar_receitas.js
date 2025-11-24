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

// Inicializa o Set e o contador a partir de inputs hidden já renderizados (quando em edição)
(function initExistingIngredients(){
    const hiddenIds = document.querySelectorAll('#hidden-ingredientes-container input[name="ingrediente[]"]');
    if (hiddenIds.length > 0) {
        hiddenIds.forEach((input) => {
            ingredientesAdicionados.add(input.value);
        });
        ingredienteIdCounter = hiddenIds.length;

        // Também move os elementos visuais já existentes para a lista caso ainda não estejam
        const listaVisual = document.getElementById('lista-ingredientes-adicionados');
        const visuals = document.querySelectorAll('#hidden-ingredientes-container .item-visual');
        visuals.forEach(v => listaVisual.appendChild(v));
    }
})();


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

