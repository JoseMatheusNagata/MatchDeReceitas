<?php
class Receita {
    public $id;
    public $usuario_id;
    public $id_tipo_receita;
    public $titulo;
    public $descricao;
    public $imagem;
    public $tempo_preparo;
    public $tempo_criacao;

    public function __construct($id, $usuario_id, $id_tipo_receita, $titulo, $descricao, $imagem, $tempo_preparo, $tempo_criacao) {
        $this->id = $id;
        $this->usuario_id = $usuario_id;
        $this->id_tipo_receita = $id_tipo_receita;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->imagem = $imagem;
        $this->tempo_preparo = $tempo_preparo;
        $this->tempo_criacao = $tempo_criacao;
    }
}
?>