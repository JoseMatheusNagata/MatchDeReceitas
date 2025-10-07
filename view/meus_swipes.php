<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Swipes</title>
    <link rel="stylesheet" href="./css/meus_swipes.css">
</head>
<body>
    <div class="container">
        <h1>Meus Swipes</h1>

        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="meusSwipes">
            <div class="filter-container">
                <label for="status">Filtrar por:</label>
                <select name="status" id="status" onchange="this.form.submit()">
                    <option value="" <?= ($status_filtro == '') ? 'selected' : '' ?>>Todos</option>
                    <option value="like" <?= ($status_filtro == 'like') ? 'selected' : '' ?>>Likes</option>
                    <option value="dislike" <?= ($status_filtro == 'dislike') ? 'selected' : '' ?>>Dislikes</option>
                </select>
            </div>
        </form>

        <div class="swipes-grid">
            <?php if (!empty($swipes)): ?>
                <?php foreach ($swipes as $swipe): ?>
                    <div class="swipe-card"> 
                        <img src="data:image/jpeg;base64,<?= base64_encode($swipe->imagem_receita) ?>" alt="Foto da Receita">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($swipe->titulo_receita) ?></h3>
                            <p>Status: <span class="status-<?= htmlspecialchars($swipe->status) ?>"><?= htmlspecialchars($swipe->status) ?></span></p>
                            
                            <form action="index.php?action=alterarStatusSwipe" method="POST" style="margin-top: 10px;">
                                <input type="hidden" name="id_receita" value="<?= $swipe->id_receita ?>">
                                <input type="hidden" name="status_atual" value="<?= $swipe->status ?>">
                                <?php
                                    $novo_status_texto = ($swipe->status == 'like') ? 'Dislike' : 'Like';
                                    $classe_botao = ($swipe->status == 'like') ? 'btn-dislike' : 'btn-like';
                                ?>
                                <button type="submit" class="btn <?= $classe_botao ?>">Mudar para <?= $novo_status_texto ?></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum swipe encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>