<?php

namespace core\controllers;

use core\classes\EnviarEmail;
use core\classes\PDF;
use core\classes\Store;
use core\Models\Usuario;
use core\Models\ModelReserva;
use DateInterval;
use DateTime;

class Reserva
{

    // ============================================================
    public function reserva_mesa()
    {

        if (isset($_SESSION['dados_reserva'])) {
            unset($_SESSION['dados_reserva']);
            Store::redirect('reservar_mesa');
            return;
        }


        $dados = [];
        // Verifica se tem usuário logado
        if (Store::logado()) {

            // buscar dados do usuário
            $usuario = new Usuario();
            $dados = ['dados_usuario' => $usuario->buscar_dados($_SESSION['user'])];
        }

        Store::Layout([
            'layouts/headerHtml',
            'layouts/nav',
            'reservar_mesa',
            'layouts/footer',
            'layouts/footerHtml',
        ], $dados);
    }

    // ============================================================
    public function reservar_mesa_submit()
    {

        //Verifica se existe asubmissão de dados
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('inicio');
            return;
        }

        // dados
        $nome = trim($_POST['nome']);
        $email = strtolower(trim($_POST['email']));
        $telefone = $_POST['telefone'];
        $numero_pessoa = trim($_POST['pessoas']);
        $mensagem = trim($_POST['mensagem']);
        $tipo_refeicao = trim($_POST['tipo_refeicao']);

        $datatime = $_POST['data_time'];
        list($data, $hora) = explode('T', $datatime);


        // define a duração e a data do termino da refeição

        /*
        cafe da manha = 45
        almoço = 1.5h
        jantar = 2h
        */

        $hora_inicio = new DateTime($hora);
        $hora_fim = '';
        switch ($tipo_refeicao) {
            case 'Café Manhã':
                $duracao = new DateInterval('PT45M');
                $hora_fim = $hora_inicio->add($duracao);
                break;

            case 'Almoço':
                $duracao = new DateInterval('PT1H30M');
                $hora_fim = $hora_inicio->add($duracao);
                break;

            case 'Jantar':
                $duracao = new DateInterval('PT2H00M');
                $hora_fim = $hora_inicio->add($duracao);
                break;
        }

        

        // validação dos dados
        // telefone
        if (!Store::validar_numero($telefone)) {
            $_SESSION['mensagem'] = 'Numero de Telefone Inválido';
            $this->reserva_mesa();
            return;
        }

        $modelreserva = new ModelReserva();

        //  Verificar as mesas disponiveis
        $mesas = $modelreserva->verifica_mesa_disponivel($numero_pessoa);

        // Verifca se ja tem uma reserva nessa mesa no mesmo periodo
        if ($mesas) {

            $id_mesa = $mesas->id_mesa;
            $resultado = $modelreserva->verifica_reserva_existente($id_mesa, $data, $hora, $hora_fim, $numero_pessoa);

            if ($resultado['status']) {

                // Caso a mesa esteja disponível
                // Prosseguir com a criação da reserva
                // coloca os dados na sessão
                $_SESSION['dados_reserva']['nome'] = $nome;
                $_SESSION['dados_reserva']['email'] = $email;
                $_SESSION['dados_reserva']['telefone'] = $telefone;
                $_SESSION['dados_reserva']['data'] = $data;
                $_SESSION['dados_reserva']['hora_inicio'] = $hora;
                $_SESSION['dados_reserva']['hora_fim'] = $hora_fim->format('H:i');
                $_SESSION['dados_reserva']['numero_pessoa'] = $numero_pessoa;
                $_SESSION['dados_reserva']['tipo_refeicao'] = $tipo_refeicao;
                $_SESSION['dados_reserva']['mensagem'] = $mensagem;
                $_SESSION['dados_reserva']['id_mesa'] = $id_mesa;


                Store::redirect('finalizar_reserva');
                return;
            } else {

                // Caso a mesa não esteja disponível
                if (isset($resultado['sugestao'])) {

                    // Existe uma mesa alternativa sugerida
                    $id_mesa_sugerida = $resultado['sugestao']['id_mesa'];

                    // coloca os dados na sessão usando outra mesa
                    $_SESSION['dados_reserva']['nome'] = $nome;
                    $_SESSION['dados_reserva']['email'] = $email;
                    $_SESSION['dados_reserva']['telefone'] = $telefone;
                    $_SESSION['dados_reserva']['data'] = $data;
                    $_SESSION['dados_reserva']['hora_inicio'] = $hora;
                    $_SESSION['dados_reserva']['hora_fim'] = $hora_fim->format('H:i');
                    $_SESSION['dados_reserva']['numero_pessoa'] = $numero_pessoa;
                    $_SESSION['dados_reserva']['tipo_refeicao'] = $tipo_refeicao;
                    $_SESSION['dados_reserva']['mensagem'] = $mensagem;
                    $_SESSION['dados_reserva']['id_mesa'] = $id_mesa_sugerida;

                    Store::redirect('finalizar_reserva');
                    return;
                } else {
                    // Não há alternativas disponíveis

                    $_SESSION['mensagem'] = "Mesa esta tenporariamente ocupado, Escolha outro Horário.";
                    $this->reserva_mesa();
                    return;
                }
            }
        } else {
            $_SESSION['mensagem'] = "Nenhuma mesa disponível encontrada para $numero_pessoa pessoa(s).";
            $this->reserva_mesa();
            return;
        }

        Store::redirect('finalizar_reserva');
    }


    // ===========================================================
    public function finalizar_reserva()
    {
        // verifica se existe cliente logado
        if (!isset($_SESSION['usuario'])) {

            // coloca na sessão um referrer temporário
            $_SESSION['tmp_reserva'] = true;

            // redirecionar para o quadro de login
            Store::redirect('login');
        } else {

            Store::redirect('reserva_resumo');
        }
    }
    // ============================================================
    public function reserva_resumo()
    {

        
        // verificar se as sessões estão vazias
        if (empty($_SESSION['dados_reserva'])) {
            Store::redirect('reservar_mesa');
            return;
        }

        // pegar os dados da session
        $dados_reserva['nome'] = $_SESSION['dados_reserva']['nome'];
        $dados_reserva['email'] = $_SESSION['dados_reserva']['email'];
        $dados_reserva['telefone'] = $_SESSION['dados_reserva']['telefone'];
        $dados_reserva['data'] = $_SESSION['dados_reserva']['data'];
        $dados_reserva['hora'] = $_SESSION['dados_reserva']['hora_inicio'];
        $dados_reserva['numero_pessoa'] = $_SESSION['dados_reserva']['numero_pessoa'];
        $dados_reserva['tipo_refeicao'] = $_SESSION['dados_reserva']['tipo_refeicao'];


        // calculo
        $valor = $this->calculo_valor_reserva($dados_reserva['numero_pessoa'], $dados_reserva['tipo_refeicao']);

        $dados_reserva['total'] = $valor;

        $id_mesa = $_SESSION['dados_reserva']['id_mesa'];

        $modelreserva = new ModelReserva();
        $mesa = $modelreserva->buscar_mesas($id_mesa);
        $dados_mesa['capacidade'] = $mesa->capacidade;
        $dados_mesa['numero_mesa'] = $mesa->numero_mesa;

        $dados = [
            'dados_reserva' => $dados_reserva,
            'dados_mesa' => $dados_mesa
        ];

        Store::Layout([
            'layouts/headerHtml',
            'layouts/nav',
            'reserva_resumo',
            'layouts/footer',
            'layouts/footerHtml',
        ], $dados);
    }

    // ============================================================
    public function cancelar_reserva()
    {

        //limpar a sessão
        unset($_SESSION['dados_reserva']);

        //redireciona para o inicio ou para o curso
        store::redirect();
    }

    // ============================================================
    public function confirmar_reserva()
    {
        //verificar se pode avançar para a gravação da incrição
        if (!isset($_SESSION['dados_reserva']) && !isset($_SESSION['dados_mesa'])) {
            $this->cancelar_reserva();
        }

        $dados_reserva['id_usuario'] = null;
        if (isset($_SESSION['user'])) {
            // id do usuario
            $dados_reserva['id_usuario'] = $_SESSION['user'];
        }

        // recessão dos dados
        $dados_reserva['nome'] = $_SESSION['dados_reserva']['nome'];
        $dados_reserva['email'] = $_SESSION['dados_reserva']['email'];
        $dados_reserva['telefone'] = $_SESSION['dados_reserva']['telefone'];
        $dados_reserva['data'] = $_SESSION['dados_reserva']['data'];
        $dados_reserva['hora_inicio'] = $_SESSION['dados_reserva']['hora_inicio'];
        $dados_reserva['hora_fim'] = $_SESSION['dados_reserva']['hora_fim'];
        $dados_reserva['numero_pessoa'] = $_SESSION['dados_reserva']['numero_pessoa'];
        $dados_reserva['tipo_refeicao'] = $_SESSION['dados_reserva']['tipo_refeicao'];
        $dados_reserva['mensagem'] = $_SESSION['dados_reserva']['mensagem'];
        $dados_reserva['id_mesa'] = $_SESSION['dados_reserva']['id_mesa'];

        // enviar o email para o cliente com os dados da reserva
        $email = new EnviarEmail;
        $resultado = $email->enviar_email_confirmacao_reserva($dados_reserva['email'], $dados_reserva);

        //limpar a sessão
        unset($_SESSION['dados_reserva']);

        $reserva = new ModelReserva;
        $reserva->guardar_reserva($dados_reserva);
        // $reserva->marcar_mesa_ocupada($dados_reserva['id_mesa']);

        $data = [
            'nome' => $dados_reserva['nome'],
            'numero' => $dados_reserva['numero_pessoa']
        ];

        Store::layout([
            'layouts/headerHtml',
            'layouts/nav',
            'reserva_sucesso',
            'layouts/footer',
            'layouts/footerHtml'
        ], $data);
    }


    // ============================================================
    public function pdf_reserva()
    {
        if (!Store::logado()) {
            Store::redirect();
            return;
        }

        $id_reserva = Store::aesDesencriptar($_GET['id']);

        $modelreserva = new ModelReserva();

        // dados da reserva
        $reserva = $modelreserva->dados_reserva($id_reserva);

        // Store::PrintData($reserva);
        // dados do cliente
        $cliente = new Usuario();
        $nome = $cliente->buscar_dados($_SESSION['user'])->nome_completo;

        $pdf = new PDF();
        $pdf->set_template(getcwd() . '\assets\tamplates\pdf_reserva.pdf');

        //prepara as configurações base do pdf
        $pdf->set_font_family('Courier New');
        $pdf->set_size('14px');

        // nome do cliente
        $pdf->posicao_dimensao(318, 317, 400, 20);
        $pdf->escrever($nome);

        $pdf->posicao_dimensao(235, 363, 400, 20);
        $pdf->escrever($reserva->tipo_refeicao);

        $pdf->posicao_dimensao(235, 389, 400, 20);
        $pdf->escrever(date('d/m/Y', strtotime($reserva->data)));

        $pdf->posicao_dimensao(235, 409, 400, 20);
        $pdf->escrever(date('H:i', strtotime($reserva->hora_inicio)));

        $pdf->posicao_dimensao(235, 429, 400, 20);
        $pdf->escrever(date('H:i', strtotime($reserva->hora_fim)));

        $pdf->posicao_dimensao(273, 473, 400, 20);
        $pdf->escrever($reserva->n_pessoa);

        $pdf->posicao_dimensao(298, 512, 400, 20);
        $pdf->escrever(date('d/m/Y H:i', strtotime($reserva->created_at)));

        switch ($reserva->estado) {
            case 'CANCELADA':
                $pdf->set_cor('red');
                break;
            case 'CONFIRMADA':
                $pdf->set_cor('rgb(1, 48, 26)');
                break;

            default:
                $pdf->set_cor('black');
                break;
        }

        $pdf->posicao_dimensao(313, 550, 400, 20);
        $pdf->escrever($reserva->estado);

        $pdf->download_pdf('comprovante_reserva.pdf');
    }

    // ============================================================
    public function cancelar_reserva_usuario()
    {

        if (!Store::logado()) {
            Store::redirect();
            return;
        }

        $id_reserva = Store::aesDesencriptar($_GET['id']);

        $modelreserva = new ModelReserva();
        $modelreserva->cancelar_reserva_usuario($id_reserva);

        Store::redirect('detalhe_reserva&id=' . Store::aesEcriptar($id_reserva));
    }


    private function calculo_valor_reserva($numero_pessoa, $tipo_refeicao)
    {
        // Definir preços por tipo de refeição

        $precos = [
            'Café Manhã' => 1000,
            'Almoço' => 1500,
            'Jantar' => 1500,
        ];


        // Verificar se o tipo de refeição existe no array
        if (!array_key_exists($tipo_refeicao, $precos)) {
            // Se não existir, pode lançar exceção, retornar 0 ou um valor padrão

            return 0;
        }

        // Calcular valor total
        $valor_total = $numero_pessoa * $precos[$tipo_refeicao];

        return $valor_total;
    }

}
