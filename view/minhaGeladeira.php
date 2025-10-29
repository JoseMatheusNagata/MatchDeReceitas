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
            
            <?php if (!empty($receitasRecomendadas)): ?>
                <div id="receitas-container">
                    <?php foreach ($receitasRecomendadas as $receita): ?>
                        <div class="receita-card"> 
                            <?php if (!empty($receita->imagem)): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($receita->imagem) ?>" alt="Foto da Receita: <?= htmlspecialchars($receita->titulo) ?>">
                            <?php else: ?>
                                <img src="img/imagem_padrao.png" alt="Imagem Padrão">
                            <?php endif; ?>

                            <div class="receita-content">
                                <h2><?= htmlspecialchars($receita->titulo) ?></h2>
                                
                                <p class="tempo-preparo"><strong>Tipo:</strong> <?= htmlspecialchars($receita->tipo_receita) ?></p>

                                <?php if (!empty($receita->tempo_preparo)): ?>
                                    <p class="tempo-preparo"><strong>Tempo de Preparo:</strong> <?= htmlspecialchars($receita->tempo_preparo) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($receita->ingredientes)): ?>
                                    <div class="ingredientes">
                                        <h3>Ingredientes:</h3>
                                        <ul>
                                            <?php foreach ($receita->ingredientes as $ingrediente): ?>
                                                <li><?= htmlspecialchars($ingrediente['quantidade']) ?> de <?= htmlspecialchars($ingrediente['nome']) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($receita->descricao)): ?>
                                    <div class="modo-preparo">
                                        <h3>Modo de Preparo:</h3>
                                        <p><?= nl2br(htmlspecialchars($receita->descricao)) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="navigation-controls">
                    <button id="prev-btn" class="nav-btn">Anterior</button>
                    <span id="receita-counter"></span>
                    <button id="next-btn" class="nav-btn">Próxima</button>
                </div>

            <?php else: ?>
                <p style="text-align: center;">Nenhuma receita encontrada com os ingredientes da sua geladeira. Tente adicionar mais itens!</p>
                <div class="navigation-controls" style="display: none;"></div> <?php endif; ?>
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
        // Define o valor do input hidden (que será enviado no form)
        document.getElementById('selected-ingrediente-id').value = id;

        // Define o valor do input de busca (para o usuário ver)
        document.getElementById('ingrediente-search').value = nome;

        // Limpa/fecha a caixa de resultados
        document.getElementById('ingrediente-search-results').innerHTML = '';
    }

    //ajax para adicionar e remover
    
    // adicionar Ingrediente
    document.getElementById('form-add-ingrediente').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const url = form.action;

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAjaxNotification(data.message || 'Ingrediente adicionado!', 'success');
                // Recarrega a página para atualizar a lista e as recomendações
                setTimeout(() => location.reload(), 1500);
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
        // Pega o token CSRF do formulário de adicionar
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAjaxNotification(data.message || 'Ingrediente removido!', 'success');
                // Recarrega a página para atualizar a lista e as recomendações
                setTimeout(() => location.reload(), 1500);
            } else {
                showAjaxNotification(data.message || 'Erro ao remover.', 'error');
            }
        })
        .catch(error => {
            console.error('Erro no fetch:', error);
            showAjaxNotification('Ocorreu um erro de comunicação.', 'error');
        });
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


    // SCRIPT DE NAVEGAÇÃO DOS CARDS 
    document.addEventListener('DOMContentLoaded', function() {
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
    });

</script>

</body>
</html>