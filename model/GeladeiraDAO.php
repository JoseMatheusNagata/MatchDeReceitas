<?php
require_once "conexao.php";
require_once "Geladeira.php";
require_once "Receita.php";

class GeladeiraDAO {

    /**
     *busca todos os ingredientes que um usuario adicionou a sua geladeira.
     */
    public function getIngredientesByUsuario($id_usuario) {
        global $pdo;
        try {
            $sql = "SELECT i.id, i.nome 
                    FROM geladeira g
                    JOIN ingrediente i ON g.id_ingrediente = i.id
                    WHERE g.id_usuario = :id_usuario
                    ORDER BY i.nome ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar ingredientes da geladeira: " . $e->getMessage());
            return [];
        }
    }

    /**
     * adiciona um ingrediente à geladeira do usuário.
     * usamos INSERT IGNORE para evitar erros de duplicidade.
     */
    public function adicionarIngrediente($id_usuario, $id_ingrediente) {
        global $pdo;
        try {
            $sql = "INSERT IGNORE INTO geladeira (id_usuario, id_ingrediente) VALUES (:id_usuario, :id_ingrediente)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_ingrediente', $id_ingrediente, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao adicionar ingrediente na geladeira: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove um ingrediente da geladeira do usuário.
     */
    public function removerIngrediente($id_usuario, $id_ingrediente) {
        global $pdo;
        try {
            $sql = "DELETE FROM geladeira WHERE id_usuario = :id_usuario AND id_ingrediente = :id_ingrediente";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_ingrediente', $id_ingrediente, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao remover ingrediente da geladeira: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca receitas que o usuário pode fazer com os ingredientes da sua geladeira.
     */
    public function buscarReceitasCompativeis($id_usuario) {
        global $pdo;
        try {
            /*
             * Lógica da Consulta:
             * -(Sub-select 'total_ingredientes_receita'): Conta quantos ingredientes cada receita exige.
             * -(Sub-select 'ingredientes_que_tenho'): Conta quantos dos ingredientes exigidos o usuário POSSUI na geladeira.
             * -(WHERE/HAVING): Filtra apenas as receitas onde o total exigido é IGUAL ao que o usuário tem,
             * e onde o total exigido é maior que zero (para não pegar receitas sem ingredientes).
             * -também exclui receitas criadas pelo próprio usuário.
             */
            $sql = "
                SELECT 
                    r.*, 
                    tr.descricao AS tipo_receita,
                    (SELECT COUNT(*) FROM receita_ingrediente ri_total WHERE ri_total.id_receita = r.id) AS total_ingredientes_receita,
                    (SELECT COUNT(g.id_ingrediente)
                     FROM receita_ingrediente ri_match
                     JOIN geladeira g ON ri_match.id_ingrediente = g.id_ingrediente
                     WHERE ri_match.id_receita = r.id AND g.id_usuario = :id_usuario
                    ) AS ingredientes_que_tenho
                FROM receita r
                JOIN tipo_receita tr ON r.id_tipo_receita = tr.id
                WHERE r.usuario_id != :id_usuario_logado
                HAVING total_ingredientes_receita > 0 AND total_ingredientes_receita = ingredientes_que_tenho
                ORDER BY r.titulo ASC
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario_logado', $id_usuario, PDO::PARAM_INT); // Usamos o mesmo ID para as duas condições
            $stmt->execute();
            
            $receitas = $stmt->fetchAll(PDO::FETCH_CLASS, 'Swipe');

            //para cada receita compatível, buscamos também os ingredientes
            foreach ($receitas as $receita) {
                $sql_ingredientes = "SELECT i.nome, ri.quantidade 
                                     FROM receita_ingrediente ri
                                     JOIN ingrediente i ON ri.id_ingrediente = i.id
                                     WHERE ri.id_receita = :id_receita";
                
                $stmt_ingredientes = $pdo->prepare($sql_ingredientes);
                $stmt_ingredientes->bindParam(':id_receita', $receita->id, PDO::PARAM_INT);
                $stmt_ingredientes->execute();
                
                $receita->ingredientes = $stmt_ingredientes->fetchAll(PDO::FETCH_ASSOC);
            }
            //retorna lista de receitas para a tela minha geladeira
            return $receitas;

        } catch (PDOException $e) {
            error_log("Erro ao buscar receitas compatíveis: " . $e->getMessage());
            return [];
        }
    }
}
?>