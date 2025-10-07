<?php
require_once "conexao.php";
require_once "Swipe.php";

class SwipeDAO {

    public function getSwipesByUsuario($id_usuario, $status_filtro = null) {
        global $pdo;
        try {
            $sql = "SELECT s.*, r.titulo as titulo_receita, r.imagem as imagem_receita
                    FROM `swipe` s 
                    JOIN receita r ON s.id_receita = r.id
                    WHERE s.id_usuario = :id_usuario";

            if ($status_filtro) {
                $sql .= " AND s.status = :status";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

            if ($status_filtro) {
                $stmt->bindParam(':status', $status_filtro, PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Swipe');

        } catch (PDOException $e) {
            echo "Erro ao buscar matches: " . $e->getMessage();
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