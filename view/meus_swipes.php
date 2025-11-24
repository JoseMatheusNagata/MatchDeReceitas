<?php require_once __DIR__ . '/../controller/AuthCheckController.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Matches</title>
    <link rel="stylesheet" href="./css/meus_swipes.css">
</head>
<body>
    <div class="container">
        <h1>Meus Matches</h1>

        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="meusSwipes"> 
            <div class="filter-container">
                <label for="titulo">Buscar por nome:</label>
                <input type="text" name="titulo" id="titulo" placeholder="Digite o nome da receita" value="<?= htmlspecialchars($titulo_filtro ?? '') ?>">
            </div>

            <div class="filter-container">
                <label for="status">Filtrar por:</label>
                <select name="status" id="status" onchange="this.form.submit()">
                    <option value="" <?= ($status_filtro == '') ? 'selected' : '' ?>>Todas</option>
                    <option value="like" <?= ($status_filtro == 'like') ? 'selected' : '' ?>>Curtidas</option>
                    <option value="dislike" <?= ($status_filtro == 'dislike') ? 'selected' : '' ?>>Não Curtidas</option>
                </select>
            </div>
            <div class="filter-container">
                <label for="id_tipo_receita">Filtrar por Categoria:</label>
                <select name="id_tipo_receita" id="id_tipo_receita" onchange="this.form.submit()">
                    <option value="">Todas as Categorias</option>
                    <?php if (isset($tiposReceita)): ?>
                        <?php foreach ($tiposReceita as $tipo): ?>
                            <option value="<?= htmlspecialchars($tipo['id']) ?>" <?= (isset($tipo_receita_filtro) && $tipo_receita_filtro == $tipo['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tipo['descricao']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

        </form>

        <div class="receitas-viewer">
            <?php if (!empty($swipes)): ?>
                <div id="receitas-container">
                    <?php foreach ($swipes as $swipe): ?>
                        <div class="receita-card"> 
                            <?php if (!empty($swipe->imagem_receita)): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($swipe->imagem_receita) ?>" alt="Foto da Receita: <?= htmlspecialchars($swipe->titulo_receita) ?>">
                            <?php else: ?>
                                <img src="img/imagem_padrao.png" alt="Imagem Padrão para <?= htmlspecialchars($swipe->titulo_receita) ?>">
                            <?php endif; ?>

                            <div class="receita-content">
                                <h2><?= htmlspecialchars($swipe->titulo_receita) ?></h2>
                                
                                <?php if (!empty($swipe->tempo_preparo_receita)): ?>
                                    <p class="tempo-preparo"><strong>Tempo de Preparo:</strong> <?= htmlspecialchars($swipe->tempo_preparo_receita) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($swipe->ingredientes)): ?>
                                    <div class="ingredientes">
                                        <h3>Ingredientes:</h3>
                                        <ul>
                                            <?php foreach ($swipe->ingredientes as $ingrediente): ?>
                                                <li><?= htmlspecialchars($ingrediente['quantidade']) ?> de <?= htmlspecialchars($ingrediente['nome']) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($swipe->descricao_receita)): ?>
                                    <div class="modo-preparo">
                                        <h3>Modo de Preparo:</h3>
                                        <p><?= nl2br(htmlspecialchars($swipe->descricao_receita)) ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <form action="index.php?action=alterarStatusSwipe" method="POST" style="margin-top: 20px;">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="id_receita" value="<?= $swipe->id_receita ?>">
                                    <input type="hidden" name="status_atual" value="<?= $swipe->status ?>">
                                    <?php
                                        $novo_status_texto = ($swipe->status == 'like') ? 'Remover das Curtidas' : 'Mover para Curtidas';
                                        $classe_botao = ($swipe->status == 'like') ? 'btn-dislike' : 'btn-like';
                                    ?>
                                    <button type="submit" class="btn <?= $classe_botao ?>"><?= $novo_status_texto ?></button>
                                </form>
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
                <p>Nenhuma receita encontrada com este filtro.</p>
            <?php endif; ?>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const receitasContainer = document.getElementById('receitas-container');
        const receitas = receitasContainer.getElementsByClassName('receita-card');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const counter = document.getElementById('receita-counter');

        let receitaAtual = 0;

        function mostrarReceita(index) {
            // Esconde todas as receitas
            for (let i = 0; i < receitas.length; i++) {
                receitas[i].style.display = 'none';
            }
            // Mostra apenas a receita atual
            if (receitas[index]) {
                receitas[index].style.display = 'block';
            }

            // Atualiza o contador
            counter.textContent = `Receita ${index + 1} de ${receitas.length}`;

            // Habilita/desabilita botões de navegação
            prevBtn.disabled = index === 0;
            nextBtn.disabled = index === receitas.length - 1;
        }

        if (receitas.length > 0) {
            prevBtn.addEventListener('click', () => {
                if (receitaAtual > 0) {
                    receitaAtual--;
                    mostrarReceita(receitaAtual);
                }
            });

            nextBtn.addEventListener('click', () => {
                if (receitaAtual < receitas.length - 1) {
                    receitaAtual++;
                    mostrarReceita(receitaAtual);
                }
            });

            // Mostra a primeira receita ao carregar a página
            mostrarReceita(0);
        } else {
            // Se não houver receitas, esconde os controles
            const navControls = document.querySelector('.navigation-controls');
            if(navControls) {
                navControls.style.display = 'none';
            }
        }
    });
</script>
<?php
    include "view/footer.php";
?>
</body>
</html>