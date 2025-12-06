<?php

namespace core\Models;

use core\classes\Database;
use core\classes\Store;
use DateTime;
use Mpdf\Tag\Select;
use Mpdf\Tag\Strong;

class ModelReserva
{

    // =====================================================================
    // verificar se tem mesas disponivel
    public function verifica_mesa_disponivel($numero_pessoa)
    {
        $db = new Database();

        $parametros = ['numero_pessoa' => $numero_pessoa];
        $query = "
        SELECT m.id_mesa, numero_mesa, capacidade
        FROM tb_mesa m
        LEFT JOIN tb_mesa_has_tb_reserva r ON m.id_mesa = r.tb_mesa_id_mesa
        WHERE m.capacidade >= :numero_pessoa 
        AND m.status = 'disponivel'
        ORDER BY m.capacidade
        LIMIT 1
        ";

        $mesa_disponivel = $db->select($query, $parametros);


        if (!empty($mesa_disponivel)) {
            return $mesa_disponivel[0]; // Retorna o único registro encontrado
        } else {
            return null; // Retorna null se nenhuma mesa estiver disponível
        }
    }

    // =====================================================================
    public function guardar_reserva($dados_reserva)
    {

        $basedados = new Database();

        $id_pessoa = '';

        // verifica se é para guardar os dados da pessoa
        if ($dados_reserva['id_usuario'] == null) {

            // se não tiver usuario cria a pessoa e devolve o seu id
            //----------------------------------------
            // guardar os dados da pessoa
            $parametros = [
                ':nome' => $dados_reserva['nome'],
                ':email' => $dados_reserva['email'],
                ':telefone' => $dados_reserva['telefone']
            ];

            $basedados->insert("
            INSERT INTO tb_pessoa VALUES(
                0,
                :nome,
                Null,
                :email,
                :telefone,
                Null
            )
            ", $parametros);

            $id_pessoa = $basedados->select("SELECT MAX(id_pessoa) id_pessoa FROM tb_pessoa")[0]->id_pessoa;
        } else {

            // se ja tiver um usuario busca o seu id-pessoa

            // buscar o id da pessoa que esta associado a um usuario
            $id_pessoa = $basedados->select("SELECT tb_pessoa_id_pessoa id_pessoa FROM tb_usuario WHERE id_usuario = :id", [':id' => $dados_reserva['id_usuario']])[0]->id_pessoa;
        }

        $dataAtual = new DateTime(); // Agora
        $dataLimitePagamento = (clone $dataAtual)->modify('+2 hours');

        // Se estiver a preparar para salvar no banco:
        $data_limite_formatada = $dataLimitePagamento->format('Y-m-d H:i:s');

        //----------------------------------------
        //guardar dados da reserva
        $parametros = [
            ':status' => 'AGUARDANDO PAGAMENTO',
            ':numero_pessoa' => $dados_reserva['numero_pessoa'],
            ':mensagem' => $dados_reserva['mensagem'],
            ':tipo_refeicao' => $dados_reserva['tipo_refeicao'],
            ':comprovativo' => Null,
            ':data_limite_pagamento' => $data_limite_formatada,
            ':id_pessoa' => $id_pessoa,
            ':id_usuario' => $dados_reserva['id_usuario'],
            ':creat' => $dataAtual->format('Y-m-d H:i:s'),
        ];

        $basedados->insert("
            INSERT INTO tb_reserva VALUES(
                0,
                :status,
                Null,
                :numero_pessoa,
                :mensagem,
                :tipo_refeicao,
                :comprovativo,
                :data_limite_pagamento,
                :id_pessoa,
                :id_usuario,
                :creat,
                NOW(),
                NULL
            )
        ", $parametros);

        $id_reserva = $basedados->select("SELECT MAX(id_reversa) id_reversa FROM tb_reserva")[0]->id_reversa;

        //----------------------------------------
        //guardar dados da relacao
        $parametros = [
            ':id_reserva' => $id_reserva,
            ':id_mesa' => $dados_reserva['id_mesa'],
            ':data' => $dados_reserva['data'],
            ':hora_inicio' => $dados_reserva['hora_inicio'],
            ':hora_fim' => $dados_reserva['hora_fim']
        ];


        $basedados->insert("
            INSERT INTO tb_mesa_has_tb_reserva VALUES(
                :id_mesa,
                :id_reserva,
                :data,
                :hora_inicio,
                :hora_fim
            )
        ", $parametros);
    }

    // =====================================================================
    public function marcar_mesa_ocupada($id_mesa)
    {

        $db = new Database();
        $parametro = ['id_mesa' => $id_mesa];
        $db->update("UPDATE tb_mesa SET status = 'reservado' WHERE id_mesa = :id_mesa", $parametro);
    }

    // =====================================================================
    public function verifica_reserva_existente($id_mesa, $data_reserva, $hora_inicio, $hora_fim, $numero_pessoa = null)
    {
        $db = new Database();

        // Parâmetros da consulta inicial
        $parametros = [
            'id_mesa' => $id_mesa,
            'data_reserva' => $data_reserva
        ];

        // Consulta para verificar se há sobreposição de horários na mesma mesa e data
        $query = "
        SELECT *
        FROM tb_mesa_has_tb_reserva
        WHERE tb_mesa_id_mesa = :id_mesa
        AND data = :data_reserva
        AND (
            (hora_inicio <= :hora_inicio AND hora_fim > :hora_inicio) OR
            (hora_inicio < :hora_fim AND hora_fim >= :hora_fim) OR
            (hora_inicio >= :hora_inicio AND hora_fim <= :hora_fim)
        )
        ";

        $parametros['hora_inicio'] = $hora_inicio;
        $parametros['hora_fim'] = $hora_fim->format('H:i');

        $reserva_existente = $db->select($query, $parametros);

        if (!empty($reserva_existente)) {
            // Se já existe reserva no período solicitado, buscar outra mesa disponível
            $query_mesa_disponivel = "
            SELECT m.id_mesa
            FROM tb_mesa AS m
            LEFT JOIN tb_mesa_has_tb_reserva AS r
            ON m.id_mesa = r.tb_mesa_id_mesa
            AND r.data = :data_reserva
            AND (
                (r.hora_inicio <= :hora_inicio AND r.hora_fim > :hora_inicio) OR
                (r.hora_inicio < :hora_fim AND r.hora_fim >= :hora_fim) OR
                (r.hora_inicio >= :hora_inicio AND r.hora_fim <= :hora_fim)
            )
            WHERE r.tb_reserva_id_reversa IS NULL
            AND m.capacidade >= :numero_pessoa
            LIMIT 1
        ";

            $parametros[':numero_pessoa'] = $numero_pessoa ?? 0;

            $mesa_disponivel = $db->select($query_mesa_disponivel, $parametros);

            if (!empty($mesa_disponivel)) {
                return [
                    'status' => false,
                    'message' => 'A mesa solicitada já está reservada, mas outra mesa está disponível.',
                    'sugestao' => [
                        'id_mesa' => $mesa_disponivel[0]->id_mesa
                    ]
                ];
            }

            return [
                'status' => false,
                'message' => 'A mesa solicitada já está reservada, e não há outras mesas disponíveis que atendam aos requisitos.'
            ];
        }

        // Reserva não existe, pode prosseguir
        return [
            'status' => true,
            'message' => 'Mesa disponível para reserva.'
        ];
    }

    // =====================================================================
    public function buscar_mesas($id_mesa)
    {

        $db = new Database();

        return $db->select("SELECT * FROM tb_mesa WHERE id_mesa = :id", [':id' => $id_mesa])[0];
    }

    // =====================================================================
    public function reservas_usuario($id_usuario)
    {

        $parametro = [':id_usuario' => $id_usuario];
        $db = new Database();

        return $db->select("
            SELECT *, r.status estado FROM tb_reserva r
            JOIN tb_mesa_has_tb_reserva mr on mr.tb_reserva_id_reversa = r.id_reversa
            JOIN tb_mesa m on mr.tb_mesa_id_mesa = m.id_mesa
            WHERE r.tb_usuario_id_usuario = :id_usuario
            order by r.created_at desc
        ", $parametro);
    }

    // =====================================================================
    public function cancelar_reserva_usuario($id_reserva)
    {

        $db = new Database();
        $db->update("UPDATE tb_reserva SET status = 'CANCELADA', updated_at = Now() WHERE id_reversa = :id", [':id' => $id_reserva]);
    }

    // =====================================================================
    public function dados_reserva($id_reserva)
    {
        $parametro = [':id_reserva' => $id_reserva];
        $db = new Database();

        return $db->select("
            SELECT *, r.status estado, p.nome_completo cliente, p.email FROM tb_reserva r
            JOIN tb_mesa_has_tb_reserva mr on mr.tb_reserva_id_reversa = r.id_reversa
            JOIN tb_mesa m on mr.tb_mesa_id_mesa = m.id_mesa
            JOIN tb_pessoa p on p.id_pessoa = r.tb_pessoa_id_pessoa
            WHERE r.id_reversa = :id_reserva
        ", $parametro)[0];
    }

    // =====================================================================
    public function total_reservas_pendentes($id)
    {

        $bd = new Database();

        $parametro = [
            ':id' => $id
        ];

        $resultados = $bd->select("
            SELECT COUNT(*) total FROM tb_reserva
            WHERE status = 'AGUARDANDO PAGAMENTO'
            AND tb_pessoa_id_pessoa = :id
        ", $parametro);

        return $resultados[0]->total;
    }

    // =====================================================================
    public function lista_reservas($filtro, $id_usuario, $data)
    {


        //vai buscar a lista de inscrições pendentes
        $basedados = new Database();

        $sql = "
            SELECT r.*, mr.*, m.numero_mesa mesa, p.id_pessoa, p.nome_completo cliente FROM tb_reserva r
            LEFT JOIN tb_mesa_has_tb_reserva mr on mr.tb_reserva_id_reversa = r.id_reversa
            LEFT JOIN tb_mesa m on mr.tb_mesa_id_mesa = m.id_mesa
            LEFT JOIN tb_pessoa p on r.tb_pessoa_id_pessoa = p.id_pessoa
        ";
        $sql .= " where 1 ";

        if ($filtro != '') {
            $sql .= " AND r.tipo_refeicao = '$filtro' ";
        }
        if (!empty($id_usuario)) {
            $sql .= "AND r.tb_usuario_id_usuario = $id_usuario";
        }


        if ($data != '') {

            $today = date("Y-m-d");
            if ($data == "today") {
                $sql .= " AND mr.data = '$today'";
            } elseif ($data == "tomorrow") {
                $tomorrow = date("Y-m-d", strtotime("+1 day"));
                $sql .= " AND mr.data = '$tomorrow'";
            } elseif ($data == "last7days") {
                $last7days = date("Y-m-d", strtotime("-7 days"));
                $sql .= " AND mr.data BETWEEN '$last7days' AND '$today'";
            } elseif ($data == "next7days") {
                $next7days = date("Y-m-d", strtotime("+7 days"));
                $sql .= " AND mr.data BETWEEN '$today' AND '$next7days'";
            } elseif ($data == "thisMonth") {
                $firstday = date("Y-m-01");
                $lastday = date("Y-m-t");
                $sql .= " AND mr.data BETWEEN '$firstday' AND '$lastday'";
            }
        }


        $sql .= " ORDER BY r.created_at desc";

        return $basedados->select($sql);


        // $basedados = new Database();
        // $resultados = $basedados->select("
        //     SELECT r.*, mr.*, m.numero_mesa mesa, p.id_pessoa, p.nome_completo cliente FROM tb_reserva r
        //     LEFT JOIN tb_mesa_has_tb_reserva mr on mr.tb_reserva_id_reversa = r.id_reversa
        //     LEFT JOIN tb_mesa m on mr.tb_mesa_id_mesa = m.id_mesa
        //     LEFT JOIN tb_pessoa p on r.tb_pessoa_id_pessoa = p.id_pessoa
        //     ORDER BY r.created_at desc
        // ");

        // // Store::PrintData($resultados);
        // return $resultados;
    }

    // =====================================================================
    public function buscar_detalhes_reserva($id_reserva)
    {
        $basedados = new Database();

        // die($id_reserva);
        return $basedados->select("
            SELECT r.*, mr.*, m.numero_mesa mesa, p.*, p.nome_completo cliente FROM tb_reserva r
            LEFT JOIN tb_mesa_has_tb_reserva mr on mr.tb_reserva_id_reversa = r.id_reversa
            LEFT JOIN tb_mesa m on mr.tb_mesa_id_mesa = m.id_mesa
            LEFT JOIN tb_pessoa p on r.tb_pessoa_id_pessoa = p.id_pessoa
            WHERE r.id_reversa = :id_reserva
        ", [':id_reserva' => $id_reserva])[0];
    }

    // =====================================================================
    public function atualizar_status_reserva($id_reserva, $estado)
    {

        // atualizar o estado da encomenda
        $bd = new Database();


        $parametros = [
            ':id_reserva' => $id_reserva,
            ':status' => $estado
        ];

        $bd->update("
             UPDATE tb_reserva
             SET
             status = :status,
             updated_at = NOW()
             WHERE id_reversa = :id_reserva
             
        ", $parametros);
    }

    // =====================================================================
    public function atualizar_presenca_reserva($id_reserva, $estado)
    {

        // atualizar o estado da encomenda
        $bd = new Database();


        $parametros = [
            ':id_reserva' => $id_reserva
        ];

        switch ($estado) {
            case 'PRESENTE':
                $bd->update("
                UPDATE tb_reserva
                SET
                presenca = 'sim',
                updated_at = NOW()
                WHERE id_reversa = :id_reserva
                
           ", $parametros);
                break;
            case 'AUSENTE':
                $bd->update("
                UPDATE tb_reserva
                SET
                presenca = 'nao',
                updated_at = NOW()
                WHERE id_reversa = :id_reserva
                
           ", $parametros);
                break;
        }
    }

    // =====================================================================
    public function atualizar_status_pedido($id_pedido, $estado)
    {

        // atualizar o estado da encomenda
        $bd = new Database();


        $parametros = [
            ':id_pedido' => $id_pedido,
            ':status' => $estado
        ];

        $bd->update("
             UPDATE tb_pedido
             SET
             status = :status,
             updated_at = NOW()
             WHERE id_pedido = :id_pedido
             
        ", $parametros);
    }

    public function salvar_comprovativo_reserva($ficheiro, $id_reserva)
    {
        $parametros = [
            ':reserva' => $id_reserva,
            ':comprovativo' => $ficheiro
        ];

        $db = new Database();
        $db->update("
            UPDATE tb_reserva set
                comprovativo = :comprovativo,
                data_limite_pagamento = Null,
                status = 'PENDENTE',
                updated_at = NOW()
            WHERE id_reversa = :reserva
        ", $parametros);
    }
}
