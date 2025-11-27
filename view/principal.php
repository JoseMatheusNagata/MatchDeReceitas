<?php
require_once __DIR__ . '/../controller/AuthCheckController.php'; 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Receitas</title>
    <link rel="stylesheet" href="./css/principal.css">
</head>
<body>


    <div class="container-principal">
        <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?>!</h1>
        <p>Aqui está um resumo do desempenho das receitas que você criou:</p>

        <div class="stats-container">
            
            <div class="stats-table">
                <h2><img src="./img/logo.png" style="width:25px; vertical-align: middle;"> Top 10 Receitas (Matches)</h2>
                
                <?php 
                if (isset($topLikes) && !empty($topLikes)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 10%;">Posição</th>
                                <th>Nome da Receita</th>
                                <th style="width: 20%;">Total de Likes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topLikes as $index => $receita): ?>
                                <tr>
                                    <td style="text-align: center;"><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($receita['titulo']) ?></td>
                                    <td class="count-like"><?= htmlspecialchars($receita['total_count']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">Suas receitas ainda não receberam 'Likes'.</p>
                <?php endif; ?>
            </div>

            <div class="stats-table">
                <h2><img src="./img/logo.png" style="width:25px; vertical-align: middle;"> Top 10 Receitas (Não Matches)</h2>
                
                <?php // Verifica se a variável $topDislikes existe e não está vazia
                if (isset($topDislikes) && !empty($topDislikes)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 10%;">Posição</th>
                                <th>Nome da Receita</th>
                                <th style="width: 20%;">Total de Dislikes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topDislikes as $index => $receita): ?>
                                <tr>
                                    <td style="text-align: center;"><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($receita['titulo']) ?></td>
                                    <td class="count-dislike"><?= htmlspecialchars($receita['total_count']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">Suas receitas ainda não receberam 'Dislikes'.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <?php
        include "view/footer.php";
    ?>
</body>    