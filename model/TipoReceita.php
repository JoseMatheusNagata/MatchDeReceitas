<?php
class TipoReceita {
    public $id;
    public $descricao;


    public function __construct($id, $descricao) {
        $this->id = $id;
        $this->descricao = $descricao;

    }
}
?>