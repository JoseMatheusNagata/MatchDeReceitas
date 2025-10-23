<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Receitas</title>
    <link rel="stylesheet" href="./css/minhas_receitas.css">
</head>
<body>
<?php
// Helper para acessar campos que podem vir como objeto ou array
function get_field($item, $key, $default = null) {
    if (is_object($item) && isset($item->$key)) return $item->$key;
    if (is_array($item) && array_key_exists($key, $item)) return $item[$key];
    return $default;
}
function safe_str($val) {
    return htmlspecialchars((string)($val ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
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
                            <option value="<?= safe_str(get_field($tipo,'id')) ?>" <?= (isset($tipo_receita_filtro) && $tipo_receita_filtro == get_field($tipo,'id')) ? 'selected' : '' ?>>
                                <?= safe_str(get_field($tipo,'descricao')) ?>
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
                        <?php
                            $imgData = get_field($receita, 'imagem_receita', null);
                            $titulo = get_field($receita, 'titulo_receita', '');
                            $tempo = get_field($receita, 'tempo_preparo_receita', '');
                            $ingredientes = get_field($receita, 'ingredientes', []);
                            $descricao = get_field($receita, 'descricao_receita', '');
                        ?>
                        <div class="receita-card"> 
                            <?php if (!empty($imgData)): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($imgData) ?>" alt="Foto da Receita: <?= safe_str($titulo) ?>">
                            <?php else: ?>
                                <img src="img/imagem_padrao.png" alt="Imagem Padrão para <?= safe_str($titulo) ?>">
                            <?php endif; ?>

                            <div class="receita-content">
                                <h2><?= safe_str($titulo) ?></h2>
                                
                                <?php if (!empty($tempo)): ?>
                                    <p class="tempo-preparo"><strong>Tempo de Preparo:</strong> <?= safe_str($tempo) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($ingredientes) && is_iterable($ingredientes)): ?>
                                    <div class="ingredientes">
                                        <h3>Ingredientes:</h3>
                                        <ul>
                                            <?php foreach ($ingredientes as $ingrediente): ?>
                                                <li>
                                                    <?= safe_str(get_field($ingrediente,'nome')) ?>
                                                    <?php $q = get_field($ingrediente,'quantidade', null); if ($q !== null && $q !== ''): ?> - <?= safe_str($q) ?><?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($descricao)): ?>
                                    <div class="descricao">
                                        <h3>Descrição:</h3>
                                        <p><?= nl2br(safe_str($descricao)) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nenhuma receita encontrada.</p>
            <?php endif; ?>

            
    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>Id</th><th>Nome</th>
        <?php foreach($pontos as $p): ?>
                <tr>
                <td><?= $p->getId() ?></td>
                <td><?= htmlspecialchars($p->getAllReceitasByUsuario()) ?></td>
                
                    
                    <a href="index.php?controller=ponto&action=form&id=<?= $p->getId() ?>">Editar</a> |
                    <form method="post" action="index.php?controller=ponto&action=excluir" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $p->getId() ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <button type="submit" onclick="return confirm('Excluir?')">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>