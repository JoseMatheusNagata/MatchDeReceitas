<?php
require_once "conexao.php";
require_once "Swipe.php";
require_once "Receita.php";

class SwipeDAO {

    public function getSwipesByUsuario($id_usuario, $status_filtro = null, $id_tipo_receita = null, $titulo_filtro = null) {
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

            if ($titulo_filtro) {
                $sql .= " AND r.titulo LIKE :titulo";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

            if ($status_filtro) {
                $stmt->bindParam(':status', $status_filtro, PDO::PARAM_STR);
            }

            if ($id_tipo_receita) {
                $stmt->bindParam(':id_tipo_receita', $id_tipo_receita, PDO::PARAM_INT);
            }

            if ($titulo_filtro) {
                $nome_like = '%' . $titulo_filtro . '%';
                $stmt->bindParam(':titulo', $nome_like, PDO::PARAM_STR);
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

    function feedDeReceitas(){
        global $pdo;
        try {
            //todas as receitas (r) e seus tipos (tr)
            //faz um LEFT JOIN com a tabela 'swipe' (s) APENAS para o usuário logado
            //onde s.id_usuario É NULO (ou seja, onde não houve match no LEFT JOIN,
            //significando que o usuário logado NUNCA deu like/dislike nessa receita)
            //E ONDE o ID do criador da receita (r.usuario_id) é DIFERENTE do usuário logado.
            $id_usuario_logado = $_SESSION['id'];
            $sql = "SELECT r.*, tr.descricao AS tipo_receita
                    FROM receita r
                    JOIN tipo_receita tr ON r.id_tipo_receita = tr.id
                    LEFT JOIN swipe s ON r.id = s.id_receita AND s.id_usuario = $id_usuario_logado
                    WHERE 
                        s.id_usuario IS NULL 
                        AND r.usuario_id != $id_usuario_logado";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            $listaFeed = $stmt->fetchAll(PDO::FETCH_CLASS, 'Swipe');
            return $listaFeed;
        } catch (PDOException $e) {
            echo "Erro ao buscar receitas para o feed: " . $e->getMessage();
            return [];
        }
    }

    function criarSwipe($valor, $status){
        global $pdo;
        try {
            $sql = "INSERT INTO swipe (id_usuario, id_receita, status) VALUES (?,?,?)";
            $id_usuario_logado = $_SESSION['id'];
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $id_receita,
                $id_ingrediente,
                $status
        ]   );
      
   
            
        } catch (PDOException $e) {
            echo "Erro ao buscar receitas para o feed: " . $e->getMessage();
            return [];
        }
    }
}
?>