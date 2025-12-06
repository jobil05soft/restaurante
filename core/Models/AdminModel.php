<?php

namespace core\Models;

use core\classes\Database;
use core\classes\Store;

class AdminModel
{


    //================================================================
    public function validar_login($usuario_admin, $senha)
    {

        // pedir informções a base de dados
        $parametros = [
            ':admin' => $usuario_admin
        ];

        $db = new Database();
        $resultado = $db->select("
            SELECT * from tb_usuario u JOIN tb_pessoa p ON u.tb_pessoa_id_pessoa = p.id_pessoa
            WHERE p.email = :admin
            AND u.deleted_at IS NULL
            AND u.ativo != 0
        ", $parametros);

        if (count($resultado) != 1) {
            //não existe usuário
            return false;
        } else {


            //temos usuario admin. ver a sua senha
            $admin = $resultado[0];
            //verificar a password
            if (!password_verify($senha, $admin->senha)) {

                //password invalida
                return false;
            } else {

                //Login valido
                // verificar o tipo de Admin
                $parametros = [
                    ':id_admin' => $admin->id_usuario
                ];

                $tipo_user = $db->select("
                    SELECT t.nivel_acesso nivel_acesso FROM tb_usuario u JOIN tb_tipo_usuario t 
                    ON u.tb_tipo_usuario_id_tipouser = t.id_tipouser
                    WHERE u.id_usuario = :id_admin
                ", $parametros)[0]->nivel_acesso;


                if ($tipo_user == 'admin') {
                    $_SESSION['tipo_admin'] = 'admin';
                }
                if ($tipo_user == 'recepcionista') {
                    $_SESSION['tipo_admin'] = 'recepcionista';
                }
                if ($tipo_user == 'atendente') {
                    $_SESSION['tipo_admin'] = 'atendente';
                }
                if ($tipo_user == 'cliente') {
                    return false;
                }

                return $admin;
            }
        }
    }

    // ===========================================================
    public function buscar_admin($id_admin)
    {
        $parametro = [':id' => $id_admin];
        $db = new Database();
        return $db->select(
            "
            SELECT * from tb_pessoa 
            join tb_usuario on tb_usuario.tb_pessoa_id_pessoa = tb_pessoa.id_pessoa
            join tb_tipo_usuario on tb_usuario.tb_tipo_usuario_id_tipouser = tb_tipo_usuario.id_tipouser
            WHERE tb_usuario.id_usuario = :id",
            $parametro
        )[0];
    }

    // ===========================================================
    public function lista_pedidos($filtro, $id_cliente)
    {
        
        $bd = new Database();
        $sql = "
            SELECT p.*, c.nome_completo FROM tb_pedido p 
            LEFT JOIN tb_usuario u ON p.tb_usuario_id_usuario = u.id_usuario
            LEFT JOIN tb_pessoa c ON u.tb_pessoa_id_pessoa = c.id_pessoa
            WHERE 1";
        $sql .= " AND p.modalidade = 'em casa'";
        if ($filtro != '') {
            $sql .= " AND p.status = '$filtro'";
        }
        if (!empty($id_cliente)) {
            $sql .= " AND p.tb_usuario_id_usuario = $id_cliente";
        }
        $sql .= " ORDER BY p.created_at desc";

        
        return $bd->select($sql);
    }

    // ===========================================================
    public function lista_instantaneos($filtro, $id_cliente)
    {
        $bd = new Database();
        $sql = "
            SELECT p.*, c.nome_completo FROM tb_pedido p 
            LEFT JOIN tb_usuario u ON p.tb_usuario_id_usuario = u.id_usuario
            LEFT JOIN tb_pessoa c ON u.tb_pessoa_id_pessoa = c.id_pessoa
            WHERE 1";
        $sql .= " AND p.modalidade = 'no local'";
        if ($filtro != '') {
            $sql .= " AND p.status = '$filtro'";
        }
        if (!empty($id_cliente)) {
            $sql .= " AND p.tb_usuario_id_usuario = $id_cliente";
        }
        $sql .= " ORDER BY p.created_at desc";
        return $bd->select($sql);
    }

    // ===========================================================
    public function buscar_detalhes_pedido($id_pedido)
    {

        // vai buscar os detalhes de uma encomenda
        $bd = new Database();

        $parametros = [
            ':id_pedido' => $id_pedido
        ];

        // buscar os dados da encomenda
        $dados_pedido = $bd->select("
            SELECT p.*, c.nome_completo cliente FROM tb_pedido p 
            LEFT JOIN tb_usuario u ON p.tb_usuario_id_usuario = u.id_usuario
            LEFT JOIN tb_pessoa c ON u.tb_pessoa_id_pessoa = c.id_pessoa
            WHERE p.id_pedido = :id_pedido
            AND u.id_usuario = p.tb_usuario_id_usuario
            ", $parametros);

        // lista de produtos do pedido
        $lista_produtos = $bd->select("
            SELECT pc.*, c.imagem 
            FROM tb_pedido_has_tb_cardapio pc
            JOIN tb_cardapio c on pc.tb_cardapio_id_item = c.id_item   
            WHERE pc.tb_pedido_id_pedido = :id_pedido", $parametros);

        return [
            'pedido' => $dados_pedido[0],
            'lista_produtos' => $lista_produtos
        ];
    }

    // ===========================================================
    public function total_pedidos()
    {

        $db = new Database();

        return $resultado = [
            'total_pedido' => $db->select("SELECT count(*) total_pedido FROM tb_pedido")[0]->total_pedido,
            'total_pedido_local' => $db->select("SELECT count(*) total_pedido_local FROM tb_pedido WHERE modalidade = 'no local'")[0]->total_pedido_local,
            'total_pedido_encomenda' => $db->select("SELECT count(*) total_pedido_encomenda FROM tb_pedido WHERE modalidade = 'em casa'")[0]->total_pedido_encomenda
        ];
    }

    // ===========================================================
    public function lista_itens_cardapio($categoria)
    {

        // buscar todas as informações dos produtos da base de dados
        $bd = new Database();

        // buscar a lista de categorias
        $categorias = $this->lista_categorias();

        $sql = "SELECT * FROM tb_cardapio ";
        $sql .= "WHERE 1 ";

        if (in_array($categoria, $categorias)) {
            $sql .= "AND categoria = '$categoria'";
        }

        $sql .= " AND deleted_at is Null";
        $produtos = $bd->select($sql);
        return $produtos;
    }

    // ===========================================================
    public function lista_categorias()
    {

        // devolve a lista de categorias existentes na base de dados
        $bd = new Database();
        $resultados = $bd->select("SELECT DISTINCT categoria FROM tb_cardapio WHERE deleted_at is null");
        $categorias = [];
        foreach ($resultados as $resultado) {
            array_push($categorias, $resultado->categoria);
        }
        return $categorias;
    }

    // ===========================================================
    public function prado_do_dia()
    {

        $db = new Database();

        $resultado = $db->select("SELECT nome_item, descricao, imagem FROM tb_cardapio WHERE prato_dia = '1'");

        if (count($resultado) == 0) {
            return 0;
        }

        return $resultado[0];
    }

    // ===========================================================
    public function adicionar_novo_item()
    {

        $db = new Database();

        $ficheiro = '';
        if (isset($_FILES)) {
            $formatos = [
                'image/png',
                'image/jpeg',
                'application/pdf'
            ];

            foreach ($_FILES as $file) {

                if (!in_array($file['type'], $formatos)) continue;

                $ficheiro = date('ymdhis') . '_menu_' . $file['name'];
                move_uploaded_file($file['tmp_name'], '../assets/img/menu/' . $ficheiro);
            }
        }

        $parametros = [
            ':prato' => trim($_POST['prato']),
            ':detalhe' => trim($_POST['detalhes']),
            ':preco' => trim($_POST['preco']),
            ':categoria' => trim($_POST['categoria']),
            ':imagem' => $ficheiro,
            ':visivel' => 1,
            ':prato_dia' => 0
        ];



        $db->insert("
        INSERT INTO tb_cardapio VALUES(
            0,
            :prato,
            :detalhe,
            :preco,
            :categoria,
            :imagem,
            :visivel,
            :prato_dia,
            Now(),
            Now(),
            NULL
        )
        ", $parametros);

    }

    public function buscar_item($id)
    {

        $db = new Database();
        $parametro = ['id' => $id];
        return $db->select("SELECT * FROM tb_cardapio WHERE id_item = :id", $parametro)[0];
    }

    public function editar_item_cardapio()
    {

        $db = new Database();


        $ficheiro = '';
        if (isset($_FILES)) {
            $formatos = [
                'image/png',
                'image/jpeg',
                'application/pdf'
            ];

            foreach ($_FILES as $file) {

                if (!in_array($file['type'], $formatos)) continue;

                $ficheiro = date('ymdhis') . '_menu_' . $file['name'];
                move_uploaded_file($file['tmp_name'], '../assets/img/menu/' . $ficheiro);
            }
        }

        if ($ficheiro == '') {

            $parametros = [
                ':id_item' => trim($_POST['id']),
                ':prato' => trim($_POST['prato']),
                ':detalhe' => trim($_POST['detalhes']),
                ':preco' => trim($_POST['preco']),
                ':categoria' => trim($_POST['categoria'])
            ];

            $db->update("
                UPDATE tb_cardapio SET
                nome_item = :prato,
                descricao = :detalhe,
                preco = :preco,
                categoria = :categoria,
                created_at = Now(),
                updated_at = Now()
            
                WHERE id_item = :id_item
            ", $parametros);
        } else {

            $parametros = [
                ':id_item' => trim($_POST['id']),
                ':prato' => trim($_POST['prato']),
                ':detalhe' => trim($_POST['detalhes']),
                ':preco' => trim($_POST['preco']),
                ':categoria' => trim($_POST['categoria']),
                ':imagem' => $ficheiro
            ];

            $db->update("
                UPDATE tb_cardapio SET
                nome_item = :prato,
                descricao = :detalhe,
                preco = :preco,
                categoria = :categoria,
                imagem = :imagem,
                created_at = Now(),
                updated_at = Now()

                WHERE id_item = :id_item
            ", $parametros);
        }
    }

    public function altera_estado_item($s, $id_item)
    {

        $db = new Database();
        $parametro = [':id' => $id_item];


        $mensagem = '';
        switch ($s) {
            case 'off':
                $db->update("UPDATE tb_cardapio SET visivel = '0' WHERE id_item = :id", $parametro);
                $mensagem = 'NOTIFICAÇÃO. O item do cardápio foi Alterado para invisible. Não vai aparecer na página do Cardápio';
                return $mensagem;
                break;
            case 'on':
                $db->update("UPDATE tb_cardapio SET visivel = '1' WHERE id_item = :id", $parametro);
                $mensagem = 'NOTIFICAÇÃO. O item do cardápio foi Alterado para visible. Esta habilitado para aparecer na página do Cardápio';
                return $mensagem;
                break;
        }

        return false;
    }

    public function delete_item_cardapio($id)
    {

        $db = new Database();
        $parametro = [':id' => $id];
        $db->update('UPDATE tb_cardapio SET deleted_at = NOW() WHERE id_item = :id', $parametro);
    }





















    public function op_remover_definir_prato_dia($acao, $id_item)
    {

        $db = new Database();
        $parametro = [':id' => $id_item];

        // VERIFICAR A AÇÃO [DEFINIR / REMOVER]
        switch ($acao) {
            case 'remover':

                $db->update("UPDATE tb_cardapio SET prato_dia = '0' WHERE id_item = :id", $parametro);
                return 'NOTIFICAÇÃO - Item Removido como prato do dia';
                break;

            default:
                # code...
                // Verifica se ja tem um prato do dia
                $prato_anterior = $db->select("SELECT * FROM tb_cardapio WHERE prato_dia = '1'");
                if ($prato_anterior) {

                    // actualiza ele para não tem mais
                    $db->update("UPDATE tb_cardapio SET prato_dia = '0' where prato_dia = '1'");

                    $db->update("UPDATE tb_cardapio SET prato_dia = '1' WHERE id_item = :id", $parametro);
                }

                $db->update("UPDATE tb_cardapio SET prato_dia = '1' WHERE id_item = :id", $parametro);

                return 'NOTIFICAÇÃO - Item Definido como prato do dia';
                break;
        }
    }
    // ===========================================================
    public function lista_clientes()
    {
        // vai buscar todos os clientes registados na base de dados
        $bd = new Database();
        $resultados = $bd->select("
            SELECT 
                p.id_pessoa, p.nome_completo, p.email, p.telefone,
                u.id_usuario, u.ativo, u.deleted_at,
                COUNT(tb_pedido.id_pedido) AS total_pedido
            FROM tb_pessoa p
            LEFT JOIN tb_usuario u ON p.id_pessoa = u.tb_pessoa_id_pessoa
            LEFT JOIN tb_tipo_usuario tipo_u 
                ON tipo_u.id_tipouser = u.tb_tipo_usuario_id_tipouser
            LEFT JOIN tb_pedido ON u.id_usuario = tb_pedido.tb_usuario_id_usuario
            WHERE tipo_u.nivel_acesso = 'cliente' OR u.id_usuario IS NULL
            GROUP BY p.id_pessoa, p.nome_completo, p.email, p.telefone, u.id_usuario, u.ativo, u.deleted_at
        ");

        // Store::PrintData($resultados);
        return $resultados;
    }

    // ===========================================================
    public function buscar_cliente($id_cliente)
    {

        $bd = new Database();

        $parametros = [
            ':id_usuario' => $id_cliente
        ];

        $resultados = $bd->select("
               SELECT 
                   u.id_usuario, created_at, ativo,
                   p.nome_completo, id_pessoa, email, telefone, endereco, data_nasc
               FROM tb_usuario u
               LEFT JOIN tb_pessoa p ON u.tb_pessoa_id_pessoa = p.id_pessoa 
               WHERE u.id_usuario = :id_usuario
           ", $parametros);

        return $resultados[0];
    }

    public function dados_pessoa($id_cliente)
    {
        $bd = new Database();

        $parametros = [
            ':id' => $id_cliente
        ];

        $resultados = $bd->select("
               SELECT 
                   id_pessoa, nome_completo, id_pessoa, email, telefone, endereco, data_nasc
               FROM tb_pessoa
               WHERE id_pessoa = :id
           ", $parametros);

        return $resultados[0];
    }
    // ===========================================================
    public function total_pedidos_cliente($id_cliente)
    {
        $parametros = [
            ':id_usuario' => $id_cliente
        ];
        $bd = new Database();
        return $bd->select("
           SELECT COUNT(*) total 
           FROM tb_pedido 
           WHERE tb_usuario_id_usuario = :id_usuario
       ", $parametros)[0]->total;
    }

    // total_reserva_cliente
    // ===========================================================
    public function total_reserva_cliente($id_cliente)
    {
        $parametros = [
            ':id_cliente' => $id_cliente
        ];
        $bd = new Database();
        return $bd->select("
           SELECT COUNT(*) total 
           FROM tb_reserva 
           WHERE tb_pessoa_id_pessoa = :id_cliente
       ", $parametros)[0]->total;
    }

    // ===========================================================
    public function buscar_pedidos_cliente($id_cliente)
    {
        // buscar todas os pedidos do cliente indicado
        $parametros = [
            ':id_cliente' => $id_cliente
        ];
        $bd = new Database();

        return $bd->select("
            SELECT * FROM tb_pedido p 
            WHERE p.tb_usuario_id_usuario = :id_cliente
            ORDER BY p.id_pedido DESC
       ", $parametros);
    }

    public function buscar_reservas_cliente($id_cliente)
    {

        $bd = new Database();

        // buscar todas as reservas do cliente indicado
        $parametros = [
            ':id_cliente' => $id_cliente
        ];

        return $bd->select("
            SELECT *, r.status estado FROM tb_reserva r
            JOIN tb_mesa_has_tb_reserva mr on mr.tb_reserva_id_reversa = r.id_reversa
            JOIN tb_mesa m on mr.tb_mesa_id_mesa = m.id_mesa
            WHERE r.tb_pessoa_id_pessoa = :id_cliente
       ", $parametros);

        /*
            SELECT *, r.status estado FROM tb_reserva r
            JOIN tb_mesa_has_tb_reserva mr on mr.tb_reserva_id_reversa = r.id_reversa
            JOIN tb_mesa m on mr.tb_mesa_id_mesa = m.id_mesa
            WHERE r.tb_usuario_id_usuario = :id_usuario
       */
    }

    public function detalhe_mesas($id)
    {

        $db = new Database();


        return $db->select("SELECT * FROM tb_mesa WHERE id_mesa = :id", [':id' => $id])[0];
    }

    public function mesas($filtro)
    {

        $db = new Database();

        $sql = "SELECT * FROM tb_mesa WHERE 1";

        if (!empty($filtro)) {
            $sql .= " AND status = '$filtro'";
        }

        $sql .= " AND deleted_at is null";
        $sql .= " ORDER BY id_mesa DESC";

        return $db->select($sql);
    }

    public function adicionar_nova_mesa()
    {
        $db = new Database();



        $parametros = [
            ':numero' => trim($_POST['numero']),
            ':capacidade' => trim($_POST['capacidade']),
            ':estado' => trim($_POST['estado']),
        ];



        $db->insert("
        INSERT INTO tb_mesa VALUES(
            0,
            :numero,
            :capacidade,
            :estado,
            Now(),
            Now(),
            Null
        )
        ", $parametros);
    }

    public function form_editar_mesa()
    {


        $db = new Database();


        $parametros = [
            ':id' => trim($_POST['id']),
            ':numero' => trim($_POST['numero']),
            ':capacidade' => trim($_POST['capacidade']),
            ':estado' => trim($_POST['estado']),
        ];

        $db->update("
                UPDATE tb_mesa SET
                numero_mesa = :numero,
                capacidade = :capacidade,
                status = :estado,
                created_at = Now(),
                updated_at = Now()
                WHERE id_mesa = :id
            ", $parametros);
    }

    public function delete_mesa($id)
    {

        $db = new Database();
        $parametro = [':id' => $id];
        $db->update('UPDATE tb_mesa SET deleted_at = NOW() WHERE id_mesa = :id', $parametro);
    }


    public function historico_reserva_mesa($numero)
    {

        $db = new Database;
        $parametro = [':id' => $numero];
        $res = $db->select("
            SELECT 
                m.numero_mesa,
                mr.data, mr.hora_inicio,
                r.status, r.n_pessoa, r.tipo_refeicao, r.created_at, r.updated_at,
                p.nome_completo, p.telefone
            FROM tb_mesa m
                JOIN tb_mesa_has_tb_reserva mr ON m.id_mesa = mr.tb_mesa_id_mesa
                JOIN tb_reserva r ON mr.tb_reserva_id_reversa = r.id_reversa
                JOIN tb_pessoa p on r.tb_pessoa_id_pessoa = p.id_pessoa
            WHERE m.id_mesa = :id
            order by mr.data 
        ", $parametro);
        return $res;
    }

    public function lista_usuarios()
    {

        // vai buscar todos os usuarios registados na base de dados
        // LEFT JOIN tb_usuario u ON p.id_pessoa = u.tb_pessoa_id_pessoa
        // LEFT JOIN tb_tipo_usuario tipo_u 
        $bd = new Database();
        $resultados = $bd->select("
            SELECT 
                u.nome_usuario, id_usuario, ativo, created_at, deleted_at,
                p.nome_completo, email, telefone,
                user.nivel_acesso
            FROM tb_usuario u
                JOIN tb_pessoa p on p.id_pessoa = u.tb_pessoa_id_pessoa
                JOIN tb_tipo_usuario user on user.id_tipouser = u.tb_tipo_usuario_id_tipouser
        ");

        // Store::PrintData($resultados);
        return $resultados;
    }

    public function op_alterar_nivel($id_usuario, $nivel)
    {

        $db = new Database();
        $parametro = [':id' => $id_usuario, ':id_tipouser' => $nivel];
        $db->update(
            "
            UPDATE tb_usuario SET 
            tb_tipo_usuario_id_tipouser = :id_tipouser,
            updated_at = NOW()
            WHERE id_usuario = :id
            ",
            $parametro
        );
    }

    public function op_desativar($id_usuario)
    {

        $db = new Database();
        $parametro = [':id' => $id_usuario];
        $db->update(
            "
            UPDATE tb_usuario SET 
            ativo = '0',
            updated_at = NOW()
            WHERE id_usuario = :id
            ",
            $parametro
        );
    }

    public function op_ativar($id_usuario)
    {

        $db = new Database();
        $parametro = [':id' => $id_usuario];
        $db->update(
            "
            UPDATE tb_usuario SET 
            ativo = '1',
            updated_at = NOW()
            WHERE id_usuario = :id
            ",
            $parametro
        );
    }

    public function op_eliminar($id_usuario)
    {

        if ($this->validar_delete($id_usuario)) {
            # code...
            return;
        }

        $db = new Database();
        $parametro = [':id' => $id_usuario];

        // verifica se é o ultimo usuário a ser deletado



        $db->update(
            "
            UPDATE tb_usuario SET 
            deleted_at = NOW(),
            updated_at = NOW()
            WHERE id_usuario = :id
            ",
            $parametro
        );
    }


    private function validar_delete($id)
    {

        $db = new Database();
        $res = $db->select("SELECT id_usuario FROM tb_usuario where tb_tipo_usuario_id_tipouser = '3'");

        if (count($res) == 1) {
            return false;
        }

        // verifica se o que estas a eliminar é o activo

        if ($id == $_SESSION['admin']) {
            return false;
        }

        return true;
    }

    public function lista_tiposUsers()
    {

        $db = new Database();
        return $db->select("SELECT * FROM tb_tipo_usuario");
    }




    public function lista_niveis(){

        $db = new Database();

        return $db->select("SELECT * FROM tb_tipo_usuario");
    }
}
