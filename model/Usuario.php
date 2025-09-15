<?php
class Usuario {
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $foto_perfil;

    public function __construct($id, $nome, $email, $senha, $foto_perfil) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->foto_perfil = $foto_perfil;
    }
}
?>