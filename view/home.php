<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match de Receitas - Encontre sua receita perfeita</title>
    <link rel="stylesheet" href="./css/home.css">
    </head>
<body>
    
    <div class="landing-container">

        <section class="hero">
            <h1>Bem-vindo ao Match de Receitas</h1>
            <p>Sua nova forma de descobrir e compartilhar pratos incríveis.</p>
            <a href="index.php?action=cadastroUsuario" class="btn-cta">Crie sua conta gratuita</a>
        </section>

        <section class="features">
            <div class="feature-item">
                <h3>Crie e Descubra</h3>
                <p>Cadastre suas próprias receitas e dê "match" nas receitas incríveis de outros usuários, como um Tinder da culinária!</p>
            </div>
            <div class="feature-item">
                <h3>Organize seus Matches</h3>
                <p>Visualize facilmente sua lista de receitas curtidas (matches) e as rejeitadas. Você pode mudar de ideia a qualquer momento!</p>
            </div>
            <div class="feature-item">
                <h3>Minha Geladeira</h3>
                <p>Adicione os ingredientes que você tem em casa e nosso sistema recomenda receitas que você pode fazer agora mesmo.</p>
            </div>
        </section>

        <section class="top-receitas">
            <h2>Receitas Populares</h2>

            <?php if (isset($topReceitas) && !empty($topReceitas)): ?>
                <div class="receitas-grid">
                    <?php foreach ($topReceitas as $receita): ?>
                        <div class="receita-card-landing">
                            <?php if (!empty($receita['imagem'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($receita['imagem']) ?>" alt="<?= htmlspecialchars($receita['titulo']) ?>">
                            <?php else: ?>
                                <img src="img/imagem_padrao.png" alt="Imagem Padrão">
                            <?php endif; ?>
                            <h4><?= htmlspecialchars($receita['titulo']) ?></h4>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center;">Ainda não temos receitas no top 5. Seja o primeiro a curtir!</p>
            <?php endif; ?>

        </section>

    </div>

    <footer class="site-footer-home">
        <p>&copy; <?= date("Y") ?> Match de Receitas. Todos os direitos reservados.</p>
    </footer>

</body>
</html>