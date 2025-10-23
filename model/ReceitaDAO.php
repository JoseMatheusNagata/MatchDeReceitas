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

            $sql = "INSERT INTO receita (usuario_id, id_tipo_receita, titulo, descricao, imagem, tempo_preparo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                $idUsuarioLogado,
                $receita->id_tipo_receita,
                $receita->titulo,
                $receita->descricao,
                $receita->imagem,
                $receita->tempo_preparo,
            ]);

            $id_receita = $pdo->lastInsertId();

            foreach ($ingredientes as $key => $id_ingrediente) {
                if (!empty($id_ingrediente) && isset($quantidades[$key])) {
                    $this->inserirIngredientesDaReceita($id_receita, $id_ingrediente, $quantidades[$key]);
                }
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
        global $pdo;
        try {
            $sql = "INSERT INTO ingrediente (nome) VALUES (?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ingrediente->nome]);
            
            // Retorna os dados do ingrediente recem criado
            return [
                'id' => $pdo->lastInsertId(),
                'nome' => $ingrediente->nome
            ];

        } catch (PDOException $e) {
            error_log("Erro ao inserir ingrediente: " . $e->getMessage());
            return null;
        }
    }

     public function getAllIngredientes() {
        global $pdo;
        $stmt = $pdo->query("SELECT id, nome FROM ingrediente ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

      public function getAllTiposReceitas() {
        global $pdo;
        $stmt = $pdo->query("SELECT id, descricao FROM tipo_receita ORDER BY descricao ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      public function getAllReceitasByUsuario() {
        global $pdo;
        $stmt = $pdo->query("SELECT Titulo from receita where usuario_id = $_SESSION[id]");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      public function excluirReceitas($id_usuario, $id_receitas) {
        global $pdo;
        try {
            $sql = "DELETE FROM receita WHERE id_usuario = :id_usuario AND id_receita = :id_receita";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_receita', $id_receitas, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao remover ingrediente da geladeira: " . $e->getMessage());
            return false;
        }
    }
    
}
?>