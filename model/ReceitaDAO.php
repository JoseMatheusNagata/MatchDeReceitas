<?php
require_once "conexao.php";
require_once "Receita.php";

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

    
}
?>