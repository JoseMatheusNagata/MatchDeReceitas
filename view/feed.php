<?php require_once __DIR__ . '/../controller/AuthCheckController.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed de Receitas</title>
    <link rel="stylesheet" href="./css/feed.css">
</head>
<body>
<div class="container">
    <h1>Feed de Receitas</h1>
    
    <div class="receitas-viewer">
        <?php if (!empty($listaFeed)): ?>
            <div id="receitas-container">
                <?php foreach ($listaFeed as $item): ?>
                    <div class="receita-card">
                        <?php if (!empty($item->imagem)): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($item->imagem) ?>" alt="Foto da Receita: <?= htmlspecialchars($item->titulo) ?>">
                        <?php else: ?>
                            <img src="img/imagem_padrao.png" alt="Imagem Padrão para <?= htmlspecialchars($item->titulo) ?>">
                        <?php endif; ?>

                        <div class="receita-content">
                            <h2><?= htmlspecialchars($item->titulo) ?></h2>

                            <?php if (!empty($item->tipo_receita)): ?>
                                <p style="color: #888; font-size: 0.9em; margin-bottom: 5px;"><?= htmlspecialchars($item->tipo_receita) ?></p>
                            <?php endif; ?>

                            <?php if (!empty($item->tempo_preparo)): ?>
                                <p class="tempo-preparo">⏱ <strong>Tempo:</strong> <?= htmlspecialchars($item->tempo_preparo) ?></p>
                            <?php endif; ?>

                            <?php if (!empty($item->descricao)): ?>
                                <div class="modo-preparo">
                                    <h3>Modo de Preparo:</h3>
                                    <p><?= nl2br(htmlspecialchars($item->descricao)) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <form action="index.php?action=salvarSwipe" method="post">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                            <input type="hidden" name="id_receita" value="<?= htmlspecialchars($item->id) ?>">
                            
                            <div class="action-buttons">
                                <button type="submit" name="acao" value="dislike" class="btn btn-dislike">
                                    ✖ Dislike
                                </button>
                                <button type="submit" name="acao" value="like" class="btn btn-like">
                                    ❤ Like
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px;">
                <p>Você já viu todas as receitas disponíveis no momento!</p>
                <a href="index.php?action=meusSwipes" style="color: #5cb85c; text-decoration: none; font-weight: bold;">Ver meus matches</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
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