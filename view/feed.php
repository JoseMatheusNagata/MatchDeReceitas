<?php require_once __DIR__ . '/../controller/AuthCheckController.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed de Receitas</title>
    <link rel="stylesheet" href="./css/meus_swipes.css">
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

                            <?php if (!empty($item->tempo_preparo)): ?>
                                <p class="tempo-preparo"><strong>Tempo de Preparo:</strong> <?= htmlspecialchars($item->tempo_preparo) ?></p>
                            <?php endif; ?>

                            <?php if (!empty($item->descricao)): ?>
                                <div class="modo-preparo">
                                    <h3>Modo de Preparo:</h3>
                                    <p><?= nl2br(htmlspecialchars($item->descricao)) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <form action="index.php?action=salvarSwipe" method="post">
                            <button type="submit" name="acao" value="like">Like</button>
                            <button type="submit" name="acao" value="deslike">Dislike</button>
                            
                        </form>
                        
       
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Nenhuma nova receita encontrada no feed.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>