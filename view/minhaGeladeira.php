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

<script>

// SCRIPT DE BUSCA AJAX DE INGREDIENTES
let debounceTimer;

    function buscarIngrediente() {
        clearTimeout(debounceTimer);

        const input = document.getElementById('ingrediente-search');
        const resultsContainer = document.getElementById('ingrediente-search-results');
        const term = input.value.trim();

        resultsContainer.innerHTML = '';

        if (term.length < 2) {
            document.getElementById('selected-ingrediente-id').value = '';
            return;
        }

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

    /**
     * chamada quando o usuário clica em um item da lista de resultados
     */
    function selecionarIngrediente(id, nome) {
        document.getElementById('selected-ingrediente-id').value = id;
        document.getElementById('ingrediente-search').value = nome;
        document.getElementById('ingrediente-search-results').innerHTML = '';
    }

// =====================================================================
// AJAX PARA ADICIONAR E REMOVER (SEM RELOAD)
// =====================================================================
    
    // adicionar Ingrediente
    document.getElementById('form-add-ingrediente').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const url = form.action;

        const idIngrediente = formData.get('id_ingrediente');
        const nomeIngrediente = document.getElementById('ingrediente-search').value; 

        if (!idIngrediente || !nomeIngrediente) {
            showAjaxNotification('Por favor, selecione um ingrediente da busca.', 'error');
            return;
        }

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAjaxNotification(data.message || 'Ingrediente adicionado!', 'success');
                
                adicionarIngredienteNaListaVisual(idIngrediente, nomeIngrediente);
                
                document.getElementById('ingrediente-search').value = '';
                document.getElementById('selected-ingrediente-id').value = '';

                // Recarrega apçenas as receitas
                recarregarReceitasRecomendadas();

            } else {
                showAjaxNotification(data.message || 'Erro ao adicionar.', 'error');
            }
        })
        .catch(error => {
            console.error('Erro no fetch:', error);
            showAjaxNotification('Ocorreu um erro de comunicação.', 'error');
        });
    });

    // 2. Remover Ingrediente
    function removerIngrediente(idIngrediente) {
        if (!confirm('Tem certeza que deseja remover este ingrediente da sua geladeira?')) {
            return;
        }

        const url = 'index.php?action=removerIngredienteGeladeira';
        const formData = new FormData();
        formData.append('id_ingrediente', idIngrediente);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAjaxNotification(data.message || 'Ingrediente removido!', 'success');
                
                removerIngredienteDaListaVisual(idIngrediente);

                recarregarReceitasRecomendadas();

            } else {
                showAjaxNotification(data.message || 'Erro ao remover.', 'error');
            }
        })
        .catch(error => {
            console.error('Erro no fetch:', error);
            showAjaxNotification('Ocorreu um erro de comunicação.', 'error');
        });
    }

// =====================================================================
// FUNÇÕES DE MANIPULAÇÃO VISUAL (DOM)
// =====================================================================

    /**
     * Adiciona o ingrediente na lista "Ingredientes na sua Geladeira"
     */
    function adicionarIngredienteNaListaVisual(id, nome) {
        // Verifica se o item já não está na lista (evita clique duplo)
        if (document.querySelector(`.item-visual[data-id="${id}"]`)) {
            return;
        }

        // Remove a mensagem "Sua geladeira está vazia"
        document.getElementById('msg-geladeira-vazia')?.remove();

        const listaContainer = document.getElementById('lista-geladeira-atual');
        const item = document.createElement('div');
        item.className = 'item-visual';
        item.setAttribute('data-id', id);
        item.innerHTML = `
            <span>${nome}</span>
            <button type="button" class="btn-remove" onclick="removerIngrediente(${id})">&times;</button>
        `;
        listaContainer.appendChild(item);
    }

    /**
     * Remove o ingrediente da lista "Ingredientes na sua Geladeira"
     */
    function removerIngredienteDaListaVisual(id) {
        document.querySelector(`.item-visual[data-id="${id}"]`)?.remove();
        
        // Verifica se a lista ficou vazia
        const listaContainer = document.getElementById('lista-geladeira-atual');
        if (listaContainer.children.length === 0) {
            listaContainer.innerHTML = '<p id="msg-geladeira-vazia">Sua geladeira está vazia.</p>';
        }
    }

    /**
     * Exibe uma notificação flutuante
     */
    function showAjaxNotification(message, type = 'success') {
        const oldAlert = document.getElementById('ajax-alert-notification');
        if (oldAlert) {
            oldAlert.remove();
        }

        const alertDiv = document.createElement('div');
        alertDiv.id = 'ajax-alert-notification';
        alertDiv.className = 'alert-notification'; 
        alertDiv.textContent = message;
        alertDiv.style.backgroundColor = (type === 'error') ? '#d9534f' : '#5cb85c';

        document.body.appendChild(alertDiv);

        setTimeout(function() {
            alertDiv.style.opacity = '0';
            setTimeout(function() {
                alertDiv.remove();
            }, 500); 
        }, 4000);
    }

// =====================================================================
// FUNÇÃO PARA RECARREGAR AS RECEITAS
// =====================================================================

    /**
     * Busca o HTML das receitas recomendadas e atualiza a página.
     */
    function recarregarReceitasRecomendadas() {
        const contentContainer = document.getElementById('receitas-recomendadas-content');
        
        contentContainer.innerHTML = '<p style="text-align: center; padding: 40px;">Buscando novas receitas com base na sua geladeira...</p>';

        fetch('index.php?action=buscarReceitasGeladeiraAJAX')
            .then(response => response.text())
            .then(html => {
                contentContainer.innerHTML = html;
                
                inicializarNavegacaoReceitas();
            })
            .catch(error => {
                console.error('Erro ao recarregar receitas:', error);
                contentContainer.innerHTML = '<p style="text-align: center; color: red;">Ocorreu um erro ao buscar as receitas.</p>';
            });
    }


// =====================================================================
// SCRIPT DE NAVEGAÇÃO DOS CARDS
// =====================================================================

    /**
     * Inicia os controles "Anterior" e "Próxima" para os cards de receita.
     */
    function inicializarNavegacaoReceitas() {
        const receitasContainer = document.getElementById('receitas-container');
        
        if (!receitasContainer) return; 

        const receitas = receitasContainer.getElementsByClassName('receita-card');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const counter = document.getElementById('receita-counter');

        let receitaAtual = 0;

        function mostrarReceita(index) {
            for (let i = 0; i < receitas.length; i++) {
                receitas[i].style.display = 'none';
            }
            if (receitas[index]) {
                receitas[index].style.display = 'block';
            }
            if (counter) {
                counter.textContent = `Receita ${index + 1} de ${receitas.length}`;
            }
            if (prevBtn) prevBtn.disabled = index === 0;
            if (nextBtn) nextBtn.disabled = index === receitas.length - 1;
        }

        if (receitas.length > 0) {
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (receitaAtual > 0) {
                        receitaAtual--;
                        mostrarReceita(receitaAtual);
                    }
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    if (receitaAtual < receitas.length - 1) {
                        receitaAtual++;
                        mostrarReceita(receitaAtual);
                    }
                });
            }
            mostrarReceita(0);
        } else {
            const navControls = document.querySelector('.navigation-controls');
            if(navControls) {
                navControls.style.display = 'none';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', inicializarNavegacaoReceitas);

</script>

</body>
</html>