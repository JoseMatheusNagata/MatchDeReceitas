<?php
require_once "conexao.php";
require_once "Swipe.php";

class SwipeDAO {

    public function getSwipesByUsuario($id_usuario, $status_filtro = null, $id_tipo_receita = null) {
        global $pdo;
        try {
            $sql = "SELECT s.*, 
                           r.titulo as titulo_receita, 
                           r.imagem as imagem_receita,
                           r.descricao as descricao_receita,
                           r.tempo_preparo as tempo_preparo_receita
                    FROM `swipe` s 
                    JOIN receita r ON s.id_receita = r.id
                    WHERE s.id_usuario = :id_usuario";

            if ($status_filtro) {
                $sql .= " AND s.status = :status";
            }

            if ($id_tipo_receita) {
                $sql .= " AND r.id_tipo_receita = :id_tipo_receita";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

            if ($status_filtro) {
                $stmt->bindParam(':status', $status_filtro, PDO::PARAM_STR);
            }

            if ($id_tipo_receita) {
                $stmt->bindParam(':id_tipo_receita', $id_tipo_receita, PDO::PARAM_INT);
            }

            $stmt->execute();
            $swipes = $stmt->fetchAll(PDO::FETCH_CLASS, 'Swipe');

            foreach ($swipes as $swipe) {
                $sql_ingredientes = "SELECT i.nome, ri.quantidade 
                                     FROM receita_ingrediente ri
                                     JOIN ingrediente i ON ri.id_ingrediente = i.id
                                     WHERE ri.id_receita = :id_receita";
                
                $stmt_ingredientes = $pdo->prepare($sql_ingredientes);
                $stmt_ingredientes->bindParam(':id_receita', $swipe->id_receita, PDO::PARAM_INT);
                $stmt_ingredientes->execute();
                
                // Adiciona a lista de ingredientes ao objeto swipe
                $swipe->ingredientes = $stmt_ingredientes->fetchAll(PDO::FETCH_ASSOC);
            }

            return $swipes;

        } catch (PDOException $e) {
            echo "Erro ao buscar swipes: " . $e->getMessage();
            return [];
        }
    }

     /**
     * Atualiza o status de um match (like/dislike) para um usuário e receita específicos.
     */
    public function mudarStatus($id_usuario, $id_receita, $novo_status) {
        global $pdo;
        try {
            $sql = "UPDATE `swipe` SET status = :novo_status WHERE id_usuario = :id_usuario AND id_receita = :id_receita";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':novo_status', $novo_status, PDO::PARAM_STR);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_receita', $id_receita, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao mudar status do match: " . $e->getMessage();
            return false;
        }
    }
}
?>