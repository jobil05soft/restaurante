<?php

namespace core\Models;

use core\classes\Database;
use DateTime;

class Pedido
{


    // ================================================================
    public function guardar_pedido($dados_pedido, $dados_produtos)
    {

        $bd = new Database();

        $dataAtual =  new DateTime(); // Agora
        $dataLimitePagamento = (clone $dataAtual)->modify('+2 hours');

        // Se estiver a preparar para salvar no banco:
        $data_limite_formatada = $dataLimitePagamento->format('Y-m-d H:i:s');


        // -----------------------------------------------------
        // guardar os dados da pedido
        $parametros = [
            ':id_usuario' => $_SESSION['user'],
            ':morada' => $dados_pedido['morada'],
            ':email' => $dados_pedido['email'],
            ':telefone' => $dados_pedido['telefone'],
            ':codigo_pedido' => $dados_pedido['codigo_pedido'],
            ':status' => $dados_pedido['status'],
            ':modalidade' => $dados_pedido['modalidade'],
            ':mesa' => $dados_pedido['mesa'],
            ':comprovativo' => NULL,
            ':data_limite' => $data_limite_formatada,
            ':created_at' => $dataAtual->format('Y-m-d H:i:s')
        ];

        $bd->insert("
          INSERT INTO tb_pedido VALUES(
              0,
              NOW(),
              :codigo_pedido,
              :status,
              :morada,
              :email,
              :telefone,
              :modalidade,
              :mesa,
              :id_usuario,
              :comprovativo,
              :data_limite,
              :created_at,
              NOW()
          )
      ", $parametros);

        // buscar o id_pedido
        $id_pedido = $bd->select("
          SELECT MAX(id_pedido) id_pedido 
          FROM tb_pedido
      ")[0]->id_pedido;

        // -----------------------------------------------
        // guardar os dados dos produtos
        foreach ($dados_produtos as $produto) {
            $parametros = [
                ':id_pedido' => $id_pedido,
                ':id_item' => $produto['id_item'],
                ':designacao' => $produto['designacao_item'],
                ':quantidade' => $produto['quantidade'],
                ':preco_unidade' => $produto['preco_unidade']
            ];

            $bd->insert("
          INSERT INTO tb_pedido_has_tb_cardapio VALUES(
              :id_pedido,
              :id_item,
              :designacao,
              :quantidade,
              :preco_unidade
          )", $parametros);
        }
    }

    // =====================================================================
    public function pedidos_usuario($id_usuario)
    {
        $parametro = [':id_usuario' => $id_usuario];
        $db = new Database();

        return $db->select("
            SELECT * FROM tb_pedido p
            WHERE p.tb_usuario_id_usuario = :id_usuario
            order by p.created_at desc
         ", $parametro);
    }

    // ================================================================
    public function verificar_pedido_cliente($id_cliente, $id_pedido)
    {
        // verificar se a pedido pertence ao cliente identificado
        $parametros = [
            ':id_usuario' => $id_cliente,
            ':id_pedido' => $id_pedido
        ];

        $bd = new Database();
        $resultado = $bd->select("
                SELECT id_pedido
                FROM tb_pedido
                WHERE id_pedido = :id_pedido
                AND tb_usuario_id_usuario = :id_usuario
            ", $parametros);

        return count($resultado) == 0 ? false : true;
    }


    // ================================================================
    public function detalhes_de_pedido($id_cliente, $id_pedido)
    {
        // vai buscar os dados da pedido e a lista dos produtos da pedido
        $parametros = [
            ':id_usuario' => $id_cliente,
            ':id_pedido' => $id_pedido
        ];

        // dados da pedido
        $bd = new Database();
        $dados_pedido = $bd->select("
            SELECT * FROM tb_pedido p
            WHERE p.id_pedido = :id_pedido
            AND p.tb_usuario_id_usuario = :id_usuario
        ", $parametros)[0];

        // dados da lista de produtos da pedido
        $parametros = [
            ':id_pedido' => $id_pedido
        ];

        $produtos_pedido = $bd->select("
            SELECT * FROM tb_pedido_has_tb_cardapio pc
            JOIN tb_cardapio c on pc.tb_cardapio_id_item = c.id_item
            WHERE pc.tb_pedido_id_pedido = :id_pedido
        ", $parametros);

        // devolver ao controlador os dados do detalhe da pedido
        return [
            'dados_pedido' => $dados_pedido,
            'produtos_pedido' => $produtos_pedido
        ];
    }



    public function mesas()
    {

        $db = new Database();
        return $db->select("SELECT numero_mesa numero FROM tb_mesa");
    }

    public function total_pedido_processamento($id){

        $bd = new Database();
        $parametro = [
            ':id' => $id
        ];
        $resultados = $bd->select("
            SELECT COUNT(*) total FROM tb_pedido
            WHERE status = 'AGUARDANDO PAGAMENTO'
            AND tb_usuario_id_usuario = :id
        ", $parametro);
        
        return $resultados[0]->total;
    }

    public function salvar_comprovativo_pedido($ficheiro, $id_pedido){
        $parametros = [
            ':pedido' => $id_pedido,
            ':comprovativo' => $ficheiro
        ];

        $db = new Database();
        $db->update("
            UPDATE tb_pedido set
                comprovativo = :comprovativo,
                data_limite = Null,
                status = 'EM PROCESSAMENTO',
                updated_at = NOW()
            WHERE id_pedido = :pedido
        ", $parametros);
        
    }
}
