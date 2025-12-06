<?php

namespace core\Models;

use core\classes\Database;
use core\classes\Store;

class Cardapio
{

    // ===========================================================
    public function all($c)
    {

        $db = new Database();

        $categorias = $this->lista_categorias();

        $sql = "SELECT * FROM tb_cardapio ";
        $sql .= "WHERE visivel = '1' ";

        if(in_array($c, $categorias)){

            $sql .= "AND categoria = '$c'";
        }

        $sql .= " AND deleted_at is null";
        return $db->select($sql);
        // Store::PrintData($produtos);
    }

    // ===========================================================
    public function lista_categorias()
    {

        // devolve a lista de categorias existentes na base de dados
        $bd = new Database();
        $resultados = $bd->select("SELECT DISTINCT categoria FROM tb_cardapio WHERE deleted_at is null");
        // Store::PrintData($resultados);
        $categorias = [];
        foreach ($resultados as $resultado) {
            array_push($categorias, $resultado->categoria);
        }
        return $categorias;
    }

    // ===========================================================
    public function buscar_produtos_por_ids($ids){

        $bd = new Database();
        return $bd->select("
            SELECT * FROM tb_cardapio
            WHERE id_item IN ($ids)
        ");
    }
}
