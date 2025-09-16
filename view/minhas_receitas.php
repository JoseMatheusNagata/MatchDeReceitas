<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Receita</title>
    <link rel="stylesheet" href="./css/minhas_receitas.css">
</head>
<body>
    <div class="form-container">
        <h2>Criar Nova Receita</h2>
        <form action="index.php?action=adicionarReceita" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="titulo">Título da Receita:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição (Modo de Preparo):</label>
                <textarea id="descricao" name="descricao"></textarea>
            </div>

            <div class="form-group">
                <label for="tempo_preparo">Tempo de Preparo:</label>
                <input type="text" id="tempo_preparo" name="tempo_preparo" placeholder="Ex: 45 minutos">
            </div>

            <div class="form-group">
                <label for="id_tipo_receita">Tipo de Receita (Categoria):</label>
                <select id="id_tipo_receita" name="id_tipo_receita" required>
                    <option value="">Selecione uma categoria</option>
                    <option value="1">Almoço</option>
                    <option value="2">Jantar</option>
                    <option value="3">Sobremesa</option>
                    <option value="4">Lanche</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="imagem">Foto da Receita:</label>
                <input type="file" id="imagem" name="imagem" accept="image/*">
            </div>


            <fieldset class="fieldset">
                <legend class="legend">Ingredientes</legend>
                
                <div class="ingredient-row">
                    <div class="form-group">
                        <label for="ingrediente">Ingrediente</label>
                        <select id="ingrediente">
                            <option value="">Selecione um ingrediente</option>
                            <option value="1">Farinha de Trigo</option>
                            <option value="2">Ovo</option>
                            <option value="3">Leite</option>
                            <option value="4">Açúcar</option>
                            <option value="5">Chocolate em Pó</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantidade">Quantidade</label>
                        <input type="text" id="quantidade" placeholder="Ex: 2 xícaras">
                    </div>
                    <button type="button" class="btn btn-add" onclick="adicionarIngrediente()">Adicionar</button>
                </div>

                <hr>
                <strong>Ingredientes Adicionados:</strong>
                <div id="lista-ingredientes-cadastrados" style="margin-top: 15px;">
                    </div>

            </fieldset>

            <br>
            <button type="submit" class="btn submit-btn">Salvar Receita Completa</button>
        </form>
    </div>


</body>
</html>