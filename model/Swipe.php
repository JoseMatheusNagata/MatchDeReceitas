<?php
class Swipe {
    public $id_usuario;
    public $id_receita;
    public $status;
    public $data_interacao;
    public $titulo_receita;
    public $imagem_receita;

    #para a tela de meus_swipe
    public $descricao_receita;
    public $tempo_preparo_receita;
    public $ingredientes = [];

    //para a tela Feed de receitas para nao dar erro de Deprecated
    public $id;
    public $usuario_id;
    public $id_tipo_receita;
    public $titulo;
    public $descricao;
    public $imagem;
    public $tempo_preparo; 
    public $data_criacao;
    public $tipo_receita;

    public ?int $total_ingredientes_receita = null;
    public ?int $ingredientes_que_tenho = null;
}
?>