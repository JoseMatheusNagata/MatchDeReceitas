<?php
require_once "conexao.php";
require_once "Usuario.php";

class UsuarioDAO {
    
    public function login($email, $senha){
        global $pdo;
        
        # Se a sessão não foi iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        # 1. Seleciona o usuário APENAS pelo e-mail.
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $sql = $pdo->prepare($sql);
        $sql->bindValue("email", $email);
        $sql->execute();

        // 2. Verifica se encontrou algum usuário com esse e-mail.
        if($sql->rowCount() > 0){
            // Pega os dados do usuário encontrado
            $dado = $sql->fetch(PDO::FETCH_ASSOC);

            #password_verify() para comparar a senha digitada com o hash do banco.
            if (password_verify($senha, $dado['senha'])) {
                $_SESSION['id'] = $dado['id']; 
                $_SESSION['nome'] = $dado['nome'];
                
                if (!empty($dado['foto_perfil'])) {
                    $_SESSION['foto_perfil'] = 'data:image/jpeg;base64,' . base64_encode($dado['foto_perfil']);
                } else {
                    $_SESSION['foto_perfil'] = null;
                }
                return true;
            }
        }
        return false;
    }

    public function inserir(Usuario $usuario) {
        global $pdo;
        try {
            $sql = "INSERT INTO usuario (nome, email, senha, foto_perfil) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario->nome,$usuario->email,$usuario->senha,$usuario->foto_perfil
            ]);
        } catch (PDOException $e) {
        echo "Erro ao inserir usuário: " . $e->getMessage();
        }

    }
}
?>