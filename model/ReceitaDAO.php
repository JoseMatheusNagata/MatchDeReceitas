<?php
require_once "conexao.php";
require_once "Receita.php";
require_once "Ingrediente.php";
#echo $_SESSION['id'];

class ReceitaDAO {
    
    public function inserirReceitaCompleta(Receita $receita, array $ingredientes, array $quantidades) {
        global $pdo;
        try {
            $pdo->beginTransaction();
            
            
            $idUsuarioLogado = $_SESSION['id'];


            $sql = "INSERT INTO receita (id, usuario_id, id_tipo_receita, titulo, descricao, imagem, tempo_preparo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            var_dump($sql);
            $stmt->execute([
                $receita->id = null,
                $receita->usuario_id = $idUsuarioLogado,
                $receita->id_tipo_receita,
                $receita->titulo,
                $receita->descricao,
                $receita->imagem,
                $receita->tempo_preparo,
        
            ]);

            $id_receita = $pdo->lastInsertId();
            foreach($ingredientes as $key => $itemIngrediente){
                $this->inserirIngredientesDaReceita($id_receita, $itemIngrediente, $quantidades[$key]);
            }

            $pdo->commit();
            return true;

        } catch (PDOException $e) {
            // Se algo deu errado, desfaz todas as alterações
            $pdo->rollBack();
            echo "Erro ao inserir receita: " . $e->getMessage();
            return false;
        }
    }

    public function inserirIngredientesDaReceita($id_receita, $id_ingrediente, $quantidades) {
        global $pdo;
        $sql = "INSERT INTO receita_ingrediente (id_receita, id_ingrediente, quantidade) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $id_receita,
            $id_ingrediente,
            $quantidades
        ]);
      
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