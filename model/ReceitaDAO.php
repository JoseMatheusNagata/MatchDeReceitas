<?php
require_once "conexao.php";
require_once "Receita.php";
require_once "Ingrediente.php";


class ReceitaDAO {
    
    public function inserir(Receita $receita) {
        global $pdo;
        try {
            $sql = "INSERT INTO receita (usuario_id, id_tipo_receita, titulo, descricao, imagem, tempo_preparo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$receita->usuario_id,
                            $receita->id_tipo_receita,
                            $receita->titulo,
                            $receita->descricao,
                            $receita->imagem,
                            $receita->tempo_preparo
            ]);
        } catch (PDOException $e) {
        echo "Erro ao inserir usuário: " . $e->getMessage();
        }

    }

    public function adicionarIngrediente(Ingrediente $ingrediente) {
            if (isset($_GET['action']) && $_GET['action'] == 'adicionarIngrediente') {
                #Verifica se o formulário foi enviado (se a requisição é POST)
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                #Verifica se o campo 'nome' não está vazio
                    if (isset($_POST['nome']) && !empty($_POST['nome'])) {
            
                        #Atribui o valor de $_POST['nome'] à variável $nome
                        $nome = htmlspecialchars(strip_tags($_POST['nome']));

                        #echo "<h1>Sucesso!</h1>";
                        #echo "<p>Nome do Ingrediente: " . $nome . "</p>";

                        global $pdo;
                        $ingrediente->$nome = $_POST['nome'];
                        try {
                            $sql = "INSERT INTO ingrediente (nome) VALUES (?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$ingrediente->$nome,
                        ]);
                        } catch (PDOException $e) {
                        echo "Erro ao inserir ingrediente: " . $e->getMessage();
                        }

                    } else {
                    echo "O nome do ingrediente não pode ser vazio.";
                }
            }
        }
    }

    public function getAllIngredientes() {
        global $pdo;
        $stmt = $pdo->query("SELECT id, nome FROM ingrediente ORDER BY nome ASC");
        return $stmt->fetchAll();
    }

      public function getAllTiposReceitas() {
        global $pdo;
        $stmt = $pdo->query("SELECT id, descricao FROM tipo_receita ORDER BY descricao ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>