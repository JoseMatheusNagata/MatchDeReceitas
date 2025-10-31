<?php

?>

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
    <div class="navigation-controls" style="display: none;"></div>
<?php endif; ?>