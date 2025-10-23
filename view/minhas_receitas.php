
<main>
    <h2>Minhas Receitas</h2>

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
        <table border="1" cellpadding="6" cellspacing="0">
            <tr><th>Id</th><th>Receita</th><th>Ações</th></tr>
            <?php foreach ($receitas as $r): ?>
                <?php
                    $id = pick_field($r, ['id','Id','id_receita'], '');
                    $titulo = pick_field($r, ['titulo','titulo_receita','Titulo'], 'Sem título');
                ?>
                <tr>
                    <td><?= safe_str($id) ?></td>
                    <td><?= safe_str($titulo) ?></td>
                    <td>
                        <!-- Atualize as actions abaixo para os nomes reais de sua rota se necessário -->
                        <a href="index.php?action=criarReceitas&id=<?= safe_str($id) ?>">Editar</a>
                        |
                        <form method="post" action="index.php?action=removerReceita" style="display:inline;">
                            <input type="hidden" name="id" value="<?= safe_str($id) ?>">
                            <input type="hidden" name="csrf_token" value="<?= safe_str($_SESSION['csrf_token'] ?? '') ?>">
                            <button type="submit" onclick="return confirm('Excluir?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</main>

