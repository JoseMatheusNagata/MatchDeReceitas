<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Receitas</title>
    <link rel="stylesheet" href="./css/minhas_receitas.css">
</head>
<body>
    <div class="container">
        <h1>Minhas Receitas</h1>

        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="minhaLista">
            
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
            <?php if (!empty($receitas)): ?>
                <div id="receitas-container">
                    <?php foreach ($receitas as $receita): ?>
                        <div class="receita-card"> 
                            <?php if (!empty($receita->imagem_receita)): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($receita->imagem_receita) ?>" alt="Foto da Receita: <?= htmlspecialchars($receita->titulo_receita) ?>">
                            <?php else: ?>
                                <img src="img/imagem_padrao.png" alt="Imagem Padrão para <?= htmlspecialchars($receita->titulo_receita) ?>">
                            <?php endif; ?>

                            <div class="receita-content">
                                <h2><?= htmlspecialchars($receita->titulo_receita) ?></h2>
                                
                                <?php if (!empty($receita->tempo_preparo_receita)): ?>
                                    <p class="tempo-preparo"><strong>Tempo de Preparo:</strong> <?= htmlspecialchars($receita->tempo_preparo_receita) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($receita->ingredientes)): ?>
                                    <div class="ingredientes">
                                        <h3>Ingredientes:</h3>
                                        <ul>
                                            <?php foreach ($receita->ingredientes as $ingrediente): ?>
                                                <li><?= htmlspecialchars($ingrediente['nome']) ?> - <?= htmlspecialchars($ingrediente['quantidade']) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($receita->descricao_receita)): ?>
                                    <div class="descricao">
                                        <h3>Descrição:</h3>
                                        <p><?= nl2br(htmlspecialchars($receita->descricao_receita)) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nenhuma receita encontrada.</p>
            <?php endif; ?>