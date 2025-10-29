<?php
require_once "conexao.php";
require_once "Receita.php";
require_once "Ingrediente.php";
#echo $_SESSION['id'];

class ReceitaDAO {
    
    /**
     * salva a receita
     */
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

    /**
     * inseri os ingredientes da receita
     */
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

    /**
     * funcao de criar ingredientes
     */
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

    /**
     * busca os tipos de receitas
     */
      public function getAllTiposReceitas() {
        global $pdo;
        $stmt = $pdo->query("SELECT id, descricao FROM tipo_receita ORDER BY descricao ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * busca as receitas criadas pelo usuario
     */
      public function getAllReceitasByUsuario() {
        global $pdo;
        $stmt = $pdo->query("SELECT Titulo from receita where usuario_id = $_SESSION[id]");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * exclui as receitas
     */
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
    
    /**
     * busca ingredientes no banco de dados com base em um termo com LIKE
     */
    public function buscarIngredientesPorNome($termo) {
        global $pdo;
        try {
            $sql = "SELECT id, nome 
                    FROM ingrediente 
                    WHERE nome LIKE :termo 
                    ORDER BY nome ASC 
                    LIMIT 20";
            
            $stmt = $pdo->prepare($sql);
            $termo_like = '%' . $termo . '%';
            $stmt->bindParam(':termo', $termo_like, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar ingredientes por nome: " . $e->getMessage());
            return [];
        }
    }
}
?>