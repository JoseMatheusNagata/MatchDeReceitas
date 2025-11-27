<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Receitas</title>
    <link rel="stylesheet" href="./css/minha_lista.css">
</head>
<body>
<main>
    <div class="container">
        <h1 class="receitas-table-h1">Minhas Receitas</h1>
        <p class="receitas-table-p">Aqui estão todas as receitas que você criou!</p>
        <p class="receitas-table-p">Para editar uma receita, clique no botão <span style="color: blue;">Editar</span>.</p>
    
    <div class="receitas-table">
    <?php
    // Helpers para compatibilidade array/objeto e saída segura
    function get_field($item, $key, $default = null) {
        if (is_object($item) && isset($item->$key)) return $item->$key;
        if (is_array($item) && array_key_exists($key, $item)) return $item[$key];
        return $default;
    }
    function pick_field($item, array $keys, $default = null) {
        foreach ($keys as $k) {
            $v = get_field($item, $k, null);
            if ($v !== null) return $v;
        }
        return $default;
    }
    function safe_str($val) {
        return htmlspecialchars((string)($val ?? ''), ENT_QUOTES, 'UTF-8');
    }
    ?>

    <?php if (empty($receitas)): ?>
        <p>Nenhuma receita encontrada.</p>
    <?php else: ?>
    <div class="table-container">

        <div class="table-stats">
        <table border="1" cellpadding="6" cellspacing="0">
            <tr><th>Posição</th><th>Receita</th><th style="width: 20%;">Ações</th></tr>
            <?php foreach ($receitas as $r): ?>
                <?php
                    $id = pick_field($r, ['id','Id','id_receita'], '');
                    $titulo = pick_field($r, ['titulo','titulo_receita','Titulo'], 'Sem título');
                ?>
                <tr>
                    <td style="text-align: center;"><?= safe_str($id) ?></td>
                    <td><?= safe_str($titulo) ?></td>
                    <td>

                        <a class="btn btn-edit btn-inline" href="index.php?action=criarReceitas&id=<?= safe_str($id) ?>">Editar</a>

                    </td>
                </tr>
            <?php endforeach; ?>
          </table>
         </div>
         </div>
        </div>
    <?php endif; ?>
    </div>
     <?php
        include "view/footer.php";
    ?>
  </body>
 </main>
</html>
