<?php


namespace core\controllers;

use core\classes\EnviarEmail;
use core\classes\PDF;
use core\classes\Store;
use core\Models\AdminModel;
use core\Models\ModelReserva;
use core\Models\Usuario;
use Mpdf\TTFontFile;

class Admin
{
    // ============================================================
    public function index()
    {

        // verifica se já existe sessão aberta (admin)
        if (!Store::Admin_logado()) {
            Store::redirect('admin_login', true);
            return;
        }

        // reservas de hoje
        $reserva = new ModelReserva();
        $reservas = $reserva->lista_reservas('', null, 'today');

        $admin_model = new AdminModel();

        $dados = [
            'lista_reservas' => $reservas,
            'totais_pedido' => $admin_model->total_pedidos(),
            'total_reservas' => count($reserva->lista_reservas('', null, '')),
        ];

        // Carrega a pagina inicial
        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/index',
            'admin/layouts/footerHtml'
        ], $dados);
    }

    // ===========================================================
    // AUTENTICAÇÃO
    // ===========================================================

    public function admin_login()
    {

        if (Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // apresenta o quadro de login
        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/login',
            'admin/layouts/footerHtml'
        ]);
    }

    // ===========================================================
    public function admin_login_submit()
    {
        // verifica se já existe um utilizador logado
        if (Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se foi efetuado o post do formulário de login do admin
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('inicio', true);
            return;
        }

        // validar se os campos vieram corretamente preenchidos
        if (
            !isset($_POST['text_admin']) ||
            !isset($_POST['text_senha']) ||
            !filter_var(trim($_POST['text_admin']), FILTER_VALIDATE_EMAIL)
        ) {
            // erro de preenchimento do formulário
            $_SESSION['erro'] = 'Login inválido';
            Store::redirect('admin_login', true);
            return;
        }

        // prepara os dados para o model
        $admin = trim(strtolower($_POST['text_admin']));
        $senha = trim($_POST['text_senha']);
        // carrega o model e verifica se login é válido
        $admin_model = new AdminModel();
        $resultado = $admin_model->validar_login($admin, $senha);

        // Store::PrintData($resultado);
        // analisa o resultado
        if (is_bool($resultado)) {

            // login inválido
            $_SESSION['erro'] = 'Login inválido';
            Store::redirect('login', true);
            return;
        } else {

            // login válido. Coloca os dados na sessão do admin
            $_SESSION['admin'] = $resultado->id_usuario;
            $_SESSION['admin_usuario'] = $resultado->email;
            $_SESSION['admin_nome'] = $resultado->nome_usuario;

            // redirecionar para a página inicial do backoffice
            Store::redirect('inicio', true);
        }
    }

    // ===========================================================
    public function logout_admin()
    {

        // faz o logout do admin da sessão
        unset($_SESSION['admin']);
        unset($_SESSION['admin_usuario']);
        unset($_SESSION['admin_nome']);

        // redirecionar para o início
        Store::redirect('inicio', true);
    }

    // ===========================================================
    public function perfil_admin()
    {


        if (!Store::Admin_logado()) {
            Store::redirect('admin_login');
            return;
        }

        $admin = new AdminModel();
        $dtemp = $admin->buscar_admin($_SESSION['admin']);

        // Store::PrintData($dtemp);
        $dados = [

            'dados_admin' => [
                'Email' => $dtemp->email,
                'Nome Completo' => $dtemp->nome_completo,
                'Nome de Usuário' => $dtemp->nome_usuario,
                'Telefone' => $dtemp->telefone,
                'Endereço' => $dtemp->endereco,
                'Tipo de Usuário' => ucfirst($dtemp->nivel_acesso),
            ],
            'dados' => $dtemp,
            'niveis' => $admin->lista_tiposUsers()
        ];

        // Carrega a pagina inicial
        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/perfil_admin',
            'admin/layouts/footerHtml'
        ], $dados);
    }

    // ===========================================================
    public function alter_dados()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verificar se foi feito o post
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('inicio', true);
            return;
        }

        // validações
        // validações dos campos 
        $camposObrigatorios = ['nome_completo', 'nome_usuario', 'email', 'telefone', 'endereco', 'tipo_user'];
        $erros = [];

        // Verificar cada campo individualmente
        foreach ($camposObrigatorios as $campo) {
            if (empty($_POST[$campo])) {
                $erros[] = '<span class="my-0 p-0">' . ucfirst(str_replace('_', ' ', $campo)) . " é obrigatório." . '</span>';
            }
        }

        // Se houver erros, armazenar e redirecionar
        if (!empty($erros)) {
            $_SESSION['alert'] = implode('<br>', $erros);
            Store::redirect('perfil_admin', true);
            return;
        }

        // validar numero do telefone
        if (!Store::validar_numero($_POST['telefone'])) {
            $_SESSION['alert'] = 'Numero de Telefone Inválido';
            Store::redirect('perfil_admin', true);
            return;
        }

        $usuario = new Usuario();

        if ($usuario->verificar_email_existe_noutra_conta($_SESSION['admin'], $_POST['email'])) {
            $_SESSION['alert'] = 'Já existe uma conta associado a esse email';
            Store::redirect('perfil_admin', true);
            return;
        }

        $usuario->actualizar_dados_admin();

        // atualize os dados na sessão
        $admin_model = new AdminModel();
        $admin = $admin_model->buscar_admin($_SESSION['admin']);

        $_SESSION['admin_usuario'] = $admin->email;
        $_SESSION['admin_nome'] = $admin->nome_usuario;

        $_SESSION['alert'] = 'Dados Alterados com sucesso!';
        Store::redirect('perfil_admin', true);
    }

    // ===========================================================
    public function alter_pass()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verificar se foi feito o post
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('inicio', true);
            return;
        }


        //validar dados
        $senha_antiga = trim($_POST['text_senha_antiga']);
        $nova_senha = trim($_POST['text_senha_nova_1']);
        $confirmar_senha = trim($_POST['text_senha_nova_2']);

        //verificar se a nova senha vem com dados
        if (strlen($nova_senha) < 6) {
            $_SESSION['alert'] = "A senha deve ter acima de 6 caracteres.";
            Store::redirect('perfil_admin', true);
            return;
        }

        //verificar se a nova senha e confirmar senha estão iguais (coensidem)
        if ($nova_senha != $confirmar_senha) {
            $_SESSION['alert'] = "As senhas não são iguais";
            Store::redirect('perfil_admin', true);
            return;
        }

        $user = new Usuario();
        //verificar se senha actual esta correta
        if (!$user->verificar_senha($_SESSION['admin'], $senha_antiga)) {
            $_SESSION['alert'] = "Senha actual Errada";
            Store::redirect('perfil_admin', true);
            return;
        }

        //verificar se a nova senha é diferente da senha actual
        if ($nova_senha == $senha_antiga) {
            $_SESSION['alert'] = "A nova senha é igual a senha actual";
            Store::redirect('perfil_admin', true);
            return;
        }

        //Actualizar a nova senha
        $user->actualizar_nova_senha($_SESSION['admin'], $nova_senha);
        Store::redirect('perfil_admin', true);
    }

    // ===========================================================
    public function adicionar_novo_user()
    {
        if (!Store::Admin_logado()) {
            Store::redirect('admin_login');
            return;
        }

        // verifica se foi efetuado o post do formulário de login do admin
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('inicio', true);
            return;
        }


        // validações dos campos 
        $camposObrigatorios = ['nome', 'email', 'telefone', 'endereco'];
        $erros = [];

        // Verificar cada campo individualmente
        foreach ($camposObrigatorios as $campo) {
            if (empty($_POST[$campo])) {
                $erros[] = ucfirst(str_replace('_', ' ', $campo)) . " é obrigatório.";
            }
        }

        // Se houver erros, armazenar e redirecionar
        if (!empty($erros)) {
            $_SESSION['erro'] = implode('<br>', $erros);
            $this->perfil_admin();
            return;
        }

        // validar numero do telefone
        if (!Store::validar_numero($_POST['telefone'])) {
            $_SESSION['erro'] = 'Numero de Telefone Inválido';
            $this->perfil_admin();
            return;
        }



        // Verifica se existe uma usuario na base de dados com o mesmo email
        $usuario = new Usuario();

        if ($usuario->verificarEmail($_POST['email'])) {
            $_SESSION['erro'] = 'Já existe uma conta associado a esse email';
            $this->perfil_admin();
            return;
        }

        // guardar usuario na base de dados
        // devolver o purl
        $purl = $usuario->salvarUsuario();

        $email = new EnviarEmail();

        $email_usuario = strtolower(trim($_POST['email']));

        $resultado = $email->enviar_email_confirmacao($email_usuario, $purl);

        if ($resultado) {
            $_SESSION['alert'] = 'Utilizador criado com sucesso! Lhe será notificado com seus dados de aceeso.';
            Store::redirect('perfil_admin');
            return;
        } else {
            $_SESSION['erro'] = 'Algo Correu mal!';
            Store::redirect('perfil_admin');

            return;
        }
    }

    // ===========================================================
    // RESERVAS
    // ===========================================================

    public function lista_reservas()
    {
        if (!Store::Admin_logado()) {
            Store::redirect('admin_login');
            return;
        }

        $data = '';
        if (isset($_GET['data'])) {
            $data = $_GET['data'];
        }

        //apresenta lista de inscrição com o filtro se for preciso
        // verifica se existe um filtro na query estring
        $filtros = [
            'almoco' => 'Almoço',
            'jantar' => 'Jantar',
            'cafe_manha' => 'Café Manhã'
        ];

        $filtro = '';
        $filtro_get = '';
        if (isset($_GET['f'])) {

            $filtro_get = $_GET['f'];
            // verifica se o filtro ou a variavel é uma chave existente no nosso array de filtro
            if (key_exists($_GET['f'], $filtros)) {
                $filtro = $filtros[$_GET['f']];
            }
        }

        // vai buscar o id do usuario
        $id_usuario = null;
        if (isset($_GET['id'])) {
            $id_usuario = Store::aesDesencriptar($_GET['id']);
        }

        // carregar a lista de reservaas

        $reservas = new ModelReserva();
        $lista_reservas = $reservas->lista_reservas($filtro, $id_usuario, $data);

        $data_get = $data;
        $datas = [
            'today' => 'Hoje',
            'tomorrow' => 'Amanhã',
            'last7days' => 'Últimos 7 dias',
            'next7days' => 'Proximos 7 dias',
            'thisMonth' => 'Este Mês'
        ];

        if (key_exists($data, $datas)) {
            $data = $datas[$data];
        }

        $dados = [
            'lista_reservas' => $lista_reservas,
            'filtro' => $filtro,
            'filtro_get' => $filtro_get,
            'data' => $data,
            'data_get' => $data_get
        ];
        // Store::PrintData($dados);

        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/lista_reserva',
            'admin/layouts/footerHtml'
        ], $dados);
    }

    // ===========================================================
    public function detalhe_reserva()
    {

        if (!Store::Admin_logado()) {
            Store::redirect('admin_login');
            return;
        }

        // vai buscar o id do usuario
        $id_reserva = null;
        if (isset($_GET['id'])) {
            $id_reserva = Store::aesDesencriptar($_GET['id']);
        }

        $modelReserva = new ModelReserva();
        $reserva = $modelReserva->buscar_detalhes_reserva($id_reserva);

        $n = $reserva->n_pessoa;
        $refeicao = $reserva->tipo_refeicao;

        $valor = $this->calculo_valor_reserva($n, $refeicao);


        $dados = [
            'reserva' => $reserva,
            'valor' => $valor
        ];

        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/detalhe_reserva',
            'admin/layouts/footerHtml'
        ], $dados);
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

    // ===========================================================
    public function reserva_alterar_estado()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        //buscar o id_reserva
        $id_reserva = null;
        if (isset($_GET['r'])) {
            $id_reserva = Store::aesDesencriptar($_GET['r']);
        }
        if (gettype($id_reserva) != 'string') {
            Store::redirect('inicio', true);
            return;
        }

        // buscar o novo estado
        $estado = null;
        if (isset($_GET['s'])) {
            $estado = $_GET['s'];
        }
        if (!in_array($estado, STATUS_RESERVA)) {
            Store::redirect('inicio', true);
            return;
        }

        // regras de negócio para gerir a reserva (novo estado)

        $modelReserva = new ModelReserva();
        // verifica se é confirmar
        if ($estado == 'CONFIRMADA' || $estado == 'REJEITADO' || $estado == 'CONCLUIDA') {

            // vefica se já tem o comprovativo
            if ($modelReserva->buscar_detalhes_reserva($id_reserva)->comprovativo == Null) {

                // nao pode confirmar
                $_SESSION['erro'] = 'Não tem um comprovativo de Pagamento!';
                Store::redirect('detalhe_reserva&id=' . $_GET['r'], true);
                return;
            }
        }


        // atualizar o estado da reserva na base de dados
        $modelReserva->atualizar_status_reserva($id_reserva, $estado);

        // executar operações baseadas no novo estado
        switch ($estado) {
            case 'PENDENTE':
                // não existem ações
                $this->operacao_notificar_cliente_mudanca_estado($id_reserva);
                break;

            case 'CONFIRMADA':
                $this->operacao_enviar_email_reserva_enviada($id_reserva);
                break;

            case 'CANCELADA':
                $this->operacao_notificar_cliente_mudanca_estado($id_reserva);
                break;

            case 'CONCLUIDA':
                $this->operacao_notificar_cliente_mudanca_estado($id_reserva);
                break;

            case 'AUSENTE':
                $this->operacao_notificar_cliente_mudanca_estado($id_reserva);
                break;
        }

        // redireciona para a página da própria reserva
        Store::redirect('detalhe_reserva&id=' . $_GET['r'], true);
    }

    // ===========================================================
    public function reserva_alterar_presenca()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        //buscar o id_reserva
        $id_reserva = null;
        if (isset($_GET['r'])) {
            $id_reserva = Store::aesDesencriptar($_GET['r']);
        }
        if (gettype($id_reserva) != 'string') {
            Store::redirect('inicio', true);
            return;
        }

        // buscar o novo estado
        $estado = null;
        if (isset($_GET['s'])) {
            $estado = $_GET['s'];
        }


        // regras de negócio para gerir a reserva (novo estado)

        // atualizar o estado da reserva na base de dados
        $modelReserva = new ModelReserva();
        $modelReserva->atualizar_presenca_reserva($id_reserva, $estado);



        // redireciona para a página da própria reserva
        Store::redirect('detalhe_reserva&id=' . $_GET['r'], true);
    }

    // ===========================================================
    public function ver_anexo()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verificar se veio o mone do bilhete
        if (!isset($_GET['n'])) {
            //redirecina para a pagina do detalhe insrição
            Store::redirect('inicio', true);
        }

        $n = $_GET['n'];


        // die($n);
        $pdf = new PDF();
        if (pathinfo($n, PATHINFO_EXTENSION) == 'pdf') {

            $pdf->set_template(CPR . $n);
            $pdf->apresentar_pdf();
        } else {

            $pdf->set_imagem(CPR . $n);
            $pdf->apresentar_pdf();
        }
    }

    public function ver_anexo_pedido()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verificar se veio o mone do bilhete
        if (!isset($_GET['n'])) {
            //redirecina para a pagina do detalhe insrição
            Store::redirect('inicio', true);
        }

        $n = $_GET['n'];


        // die($n);
        $pdf = new PDF();
        if (pathinfo($n, PATHINFO_EXTENSION) == 'pdf') {

            $pdf->set_template(CPP . $n);
            $pdf->apresentar_pdf();
        } else {

            $pdf->set_imagem(CPP . $n);
            $pdf->apresentar_pdf();
        }
    }
    // ===========================================================
    // OPERAÇÕES APÓS MUDANÇA DE ESTADO [reserva]
    // ===========================================================

    private function operacao_notificar_cliente_mudanca_estado($id_reserva)
    {
        // vai enviar um email para o cliente indicando que a reserva sofreu alterações
        $modelReserva = new ModelReserva();
        $reserva = $modelReserva->buscar_detalhes_reserva($id_reserva);

        $email = new EnviarEmail();
        $email->email_notificar_cliente_mudanca_estado($reserva);
    }

    // ===========================================================
    private function operacao_enviar_email_reserva_enviada($id_reserva)
    {
        // executar as operações para enviar email ao cliente.
        $modelReserva = new ModelReserva();
        $reserva = $modelReserva->buscar_detalhes_reserva($id_reserva);

        // email
        $email = $reserva->email;

        // Store::PrintData($reserva);
        // Instancia da classe PDF
        $pdf = new PDF();
        $pdf->set_template(getcwd() . '\tamplates\pdf_reserva.pdf');

        //prepara as configurações base do pdf
        $pdf->set_font_family('Courier New');
        $pdf->set_size('14px');

        // nome do cliente
        $pdf->posicao_dimensao(318, 317, 400, 20);
        $pdf->escrever($reserva->cliente);

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

        switch ($reserva->status) {
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
        $pdf->escrever($reserva->status);

        // guardar o pdf
        $ficheiro = 'comprovante_reserva' . date('ymdHis') . '.pdf';
        $pdf->salvar_pdf($ficheiro);



        // enviar email com o ficheiro em anexo
        $email = new EnviarEmail();
        $email->enviar_pdf_reserva($reserva->email, $ficheiro);

        // eliminar o pdf
        unlink(PDF_PATH . $ficheiro);
    }


    // ===========================================================
    public function criar_pdf_reserva()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        //buscar o id_reserva
        $id_reserva = null;
        if (isset($_GET['r'])) {
            $id_reserva = Store::aesDesencriptar($_GET['r']);
        }
        if (gettype($id_reserva) != 'string') {
            Store::redirect('inicio', true);
            return;
        }

        $modelreserva = new ModelReserva();

        // dados da reserva
        $reserva = $modelreserva->dados_reserva($id_reserva);

        // Store::PrintData($reserva);

        $pdf = new PDF();
        $pdf->set_template(getcwd() . '\tamplates\pdf_reserva.pdf');

        //prepara as configurações base do pdf
        $pdf->set_font_family('Courier New');
        $pdf->set_size('14px');

        // nome do cliente
        $pdf->posicao_dimensao(318, 317, 400, 20);
        $pdf->escrever($reserva->cliente);

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


        $pdf->apresentar_pdf();
    }

    // ===========================================================
    public function enviar_pdf_reserva()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        //buscar o id_reserva
        $id_reserva = null;
        if (isset($_GET['r'])) {
            $id_reserva = Store::aesDesencriptar($_GET['r']);
        }
        if (gettype($id_reserva) != 'string') {
            Store::redirect('inicio', true);
            return;
        }

        $modelreserva = new ModelReserva();

        // dados da reserva
        $reserva = $modelreserva->dados_reserva($id_reserva);

        // Store::PrintData($reserva);
        // Instancia da classe PDF
        $pdf = new PDF();
        $pdf->set_template(getcwd() . '\tamplates\pdf_reserva.pdf');

        //prepara as configurações base do pdf
        $pdf->set_font_family('Courier New');
        $pdf->set_size('14px');

        // nome do cliente
        $pdf->posicao_dimensao(318, 317, 400, 20);
        $pdf->escrever($reserva->cliente);

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

        // guardar o pdf
        $ficheiro = 'comprovante_reserva' . date('ymdHis') . '.pdf';
        $pdf->salvar_pdf($ficheiro);


        // enviar email com o ficheiro em anexo
        $email = new EnviarEmail();
        $email->enviar_pdf_reserva($reserva->email, $ficheiro);

        // eliminar o pdf
        unlink(PDF_PATH . $ficheiro);

        Store::redirect('detalhe_reserva&id=' . $_GET['r'], true);
    }


    // ===========================================================
    // OPERAÇÕES DE PEDIDO
    // ===========================================================

    public function lista_pedidos()
    {

        if (!Store::Admin_logado()) {
            Store::redirect('admin_login');
            return;
        }

        //apresenta lista de inscrição com o filtro se for preciso
        // verifica se existe um filtro na query estring
        $filtros = [
            'pendente' => 'AGUARDANDO PAGAMENTO',
            'em_processamento' => 'EM PROCESSAMENTO',
            'confirmada' => 'CONFIRMADA',
            'cancelada' => 'CANCELADA',
            'enviada' => 'ENVIADA',
            'concluida' => 'CONCLUIDA',
        ];

        $filtro = '';
        if (isset($_GET['f'])) {

            // verifica se o filtro ou a variavel é uma chave existente no nosso array de filtro
            if (key_exists($_GET['f'], $filtros)) {
                $filtro = $filtros[$_GET['f']];
            }
        }

        // vai buscar o id do usuario
        $id_usuario = null;
        if (isset($_GET['c'])) {
            $id_usuario = Store::aesDesencriptar($_GET['c']);
        }

        // carregar a lista de pedidos

        $admin_model = new AdminModel();
        $lista_pedidos = $admin_model->lista_pedidos($filtro, $id_usuario);


        // Store::PrintData($lista_pedidos);
        $dados = [
            'lista_pedidos' => $lista_pedidos,
            'filtro' => $filtro,
        ];

        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/lista_pedidos',
            'admin/layouts/footerHtml'
        ], $dados);
    }

    // ===========================================================
    public function pedidos_instantaneos()
    {

        if (!Store::Admin_logado()) {
            Store::redirect('admin_login');
            return;
        }

        //apresenta lista de inscrição com o filtro se for preciso
        // verifica se existe um filtro na query estring
        $filtros = [
            'pendente' => 'AGUARDANDO PAGAMENTO',
            'em_processamento' => 'EM PROCESSAMENTO',
            'confirmada' => 'CONFIRMADA',
            'cancelada' => 'CANCELADA',
            'enviada' => 'ENVIADA',
            'concluida' => 'CONCLUIDA',
        ];

        $filtro = '';
        if (isset($_GET['f'])) {

            // verifica se o filtro ou a variavel é uma chave existente no nosso array de filtro
            if (key_exists($_GET['f'], $filtros)) {
                $filtro = $filtros[$_GET['f']];
            }
        }

        // vai buscar o id do usuario
        $id_usuario = null;
        if (isset($_GET['c'])) {
            $id_usuario = Store::aesDesencriptar($_GET['c']);
        }

        // carregar a lista de pedidos

        $admin_model = new AdminModel();
        $lista_instantaneos = $admin_model->lista_instantaneos($filtro, $id_usuario);


        // Store::PrintData($lista_pedidos);
        $dados = [
            'lista_pedidos' => $lista_instantaneos,
            'filtro' => $filtro,
        ];

        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/lista_instantaneos',
            'admin/layouts/footerHtml'
        ], $dados);
    }

    // ===========================================================
    public function detalhe_pedido()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        //buscar o id_encomenda
        $id_pedido = null;
        if (isset($_GET['id'])) {
            $id_pedido = Store::aesDesencriptar($_GET['id']);
        }
        if (gettype($id_pedido) != 'string') {
            Store::redirect('inicio', true);
            return;
        }

        //carregar os dados da encomenda selecionada
        $admin_model = new AdminModel();
        $pedido = $admin_model->buscar_detalhes_pedido($id_pedido);


        $total = 0;
        foreach ($pedido['lista_produtos'] as $produto) {
            $total += ($produto->quantidade * $produto->preco);
        }

        //apresentar os dados por forma a poder ver os detalhes e alterar o seu status
        $dados = $pedido;
        $dados['total'] = $total;
        // Store::PrintData($total);

        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/historico_pedidos_detalhe',
            'admin/layouts/footerHtml'
        ], $dados);
    }


    // ===========================================================
    public function pedido_alterar_estado()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        //buscar o id_pedido
        $id_pedido = null;
        if (isset($_GET['p'])) {
            $id_pedido = Store::aesDesencriptar($_GET['p']);
        }
        if (gettype($id_pedido) != 'string') {
            Store::redirect('inicio', true);
            return;
        }

        // buscar o novo estado
        $estado = null;
        if (isset($_GET['s'])) {
            $estado = $_GET['s'];
        }
        if (!in_array($estado, STATUS)) {
            Store::redirect('inicio', true);
            return;
        }

        // atualizar o estado do pedido na base de dados
        $modelReserva = new ModelReserva();
        $modelReserva->atualizar_status_pedido($id_pedido, $estado);

        // executar operações baseadas no novo estado
        switch ($estado) {
            case 'PENDENTE':
                // não existem ações
                break;

            case 'EM PROCESSAMENTO':
                $this->op_notificar_cliente_mudanca_estado($id_pedido);

                break;

            case 'ENVIADA':
                // enviar um email com a notificação ao cliente sobre o envio do pedido
                $this->op_notificar_cliente_mudanca_estado($id_pedido);
                $this->op_enviar_email_pedido_enviado($id_pedido);
                break;

            case 'CANCELADA':
                $this->op_notificar_cliente_mudanca_estado($id_pedido);
                break;

            case 'CONCLUIDA':
                $this->op_notificar_cliente_mudanca_estado($id_pedido);
                break;
        }


        // redireciona para a página da própria do pedido
        Store::redirect('detalhe_pedido&id=' . $_GET['p'], true);
    }

    // ===========================================================
    public function criar_pdf_pedido()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        //buscar o id_reserva
        $id_pedido = null;
        if (isset($_GET['id'])) {
            $id_pedido = Store::aesDesencriptar($_GET['id']);
        }
        if (gettype($id_pedido) != 'string') {
            Store::redirect('inicio', true);
            return;
        }

        $admin_model = new AdminModel();
        $pedido = $admin_model->buscar_detalhes_pedido($id_pedido);

        // Store::PrintData($pedido);

        $pdf = new PDF();
        $pdf->set_template(getcwd() . '\tamplates\pdf_pedido.pdf');



        //prepara as configurações base do pdf
        $pdf->set_font_family('Courier New');
        $pdf->set_size('10px');
        $pdf->set_cor('#fff');
        $pdf->set_font_weight('bold');


        $pdf->posicao_dimensao(80, 153, 400, 20);
        $pdf->escrever('Data: ' . date('d/m/Y', strtotime($pedido['pedido']->data_pedido)) . ' | Código: ' . $pedido['pedido']->codigo_pedido);

        $pdf->set_size('14px');
        $pdf->set_cor('black');
        $pdf->set_align('right');
        $pdf->set_font_weight('900');

        // nome do cliente
        $pdf->posicao_dimensao(402, 240, 300, 20);
        $pdf->escrever($pedido['pedido']->cliente);

        $pdf->posicao_dimensao(402, 260, 300, 20);
        $telefone = empty($pedido['pedido']->telefone) ? '' : ' - ' . $pedido['pedido']->telefone;
        $pdf->escrever($pedido['pedido']->email . $telefone);

        $pdf->posicao_dimensao(402, 280, 300, 20);
        $pdf->escrever(ucfirst($pedido['pedido']->morada));

        // apresentação da lista de produtos
        $x = 430;
        $total_pedido = 0;
        $pdf->set_font_weight('regular');
        foreach ($pedido['lista_produtos'] as $produto) {

            // localização da apresentação da descrição
            $pdf->set_align('left');
            $pdf->posicao_dimensao(100, $x, 217, 20);
            $pdf->escrever($produto->designacao_item);

            // quantidade
            $pdf->set_align('right');
            $pdf->posicao_dimensao(350, $x, 160, 20);
            $pdf->escrever($produto->quantidade);

            // preço
            $pdf->posicao_dimensao(520, $x, 188, 20);
            $preco = $produto->quantidade * $produto->preco;
            $pdf->escrever(number_format($preco, 2, ',', '.') . ' kz');

            $total_pedido += $preco;
            $x += 28;
        }

        // preço
        $pdf->set_align('right');
        $pdf->set_font_weight('bold');
        $pdf->set_size('19px');
        $pdf->set_cor('white');


        $pdf->posicao_dimensao(450, 727, 263, 28);
        $pdf->escrever('Total: ' . number_format($total_pedido, 2, ',', '.') . 'kz');

        $pdf->apresentar_pdf();
    }

    // ===========================================================
    public function enviar_pdf_pedido()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        //buscar o id_reserva
        $id_pedido = null;
        if (isset($_GET['id'])) {
            $id_pedido = Store::aesDesencriptar($_GET['id']);
        }
        if (gettype($id_pedido) != 'string') {
            Store::redirect('inicio', true);
            return;
        }

        $admin_model = new AdminModel();
        $pedido = $admin_model->buscar_detalhes_pedido($id_pedido);

        // Store::PrintData($pedido);

        $pdf = new PDF();
        $pdf->set_template(getcwd() . '\tamplates\pdf_pedido.pdf');



        //prepara as configurações base do pdf
        $pdf->set_font_family('Courier New');
        $pdf->set_size('10px');
        $pdf->set_cor('#fff');
        $pdf->set_font_weight('bold');


        $pdf->posicao_dimensao(80, 153, 400, 20);
        $pdf->escrever('Data: ' . date('d/m/Y', strtotime($pedido['pedido']->data_pedido)) . ' | Código: ' . $pedido['pedido']->codigo_pedido);

        $pdf->set_size('14px');
        $pdf->set_cor('black');
        $pdf->set_align('right');
        $pdf->set_font_weight('900');

        // nome do cliente
        $pdf->posicao_dimensao(402, 240, 300, 20);
        $pdf->escrever($pedido['pedido']->cliente);

        $pdf->posicao_dimensao(402, 260, 300, 20);
        $telefone = empty($pedido['pedido']->telefone) ? '' : ' - ' . $pedido['pedido']->telefone;
        $pdf->escrever($pedido['pedido']->email . $telefone);

        $pdf->posicao_dimensao(402, 280, 300, 20);
        $pdf->escrever(ucfirst($pedido['pedido']->morada));

        // apresentação da lista de produtos
        $x = 430;
        $total_pedido = 0;
        $pdf->set_font_weight('regular');
        foreach ($pedido['lista_produtos'] as $produto) {

            // localização da apresentação da descrição
            $pdf->set_align('left');
            $pdf->posicao_dimensao(100, $x, 217, 20);
            $pdf->escrever($produto->designacao_item);

            // quantidade
            $pdf->set_align('right');
            $pdf->posicao_dimensao(350, $x, 160, 20);
            $pdf->escrever($produto->quantidade);

            // preço
            $pdf->posicao_dimensao(520, $x, 188, 20);
            $preco = $produto->quantidade * $produto->preco;
            $pdf->escrever(number_format($preco, 2, ',', '.') . ' kz');

            $total_pedido += $preco;
            $x += 28;
        }

        // preço
        $pdf->set_align('right');
        $pdf->set_font_weight('bold');
        $pdf->set_size('19px');
        $pdf->set_cor('white');


        $pdf->posicao_dimensao(450, 727, 263, 28);
        $pdf->escrever('Total: ' . number_format($total_pedido, 2, ',', '.') . 'kz');


        // guardar o pdf
        $ficheiro = 'comprovante_pedido' . date('ymdHis') . '.pdf';
        $pdf->salvar_pdf($ficheiro);


        // enviar email com o ficheiro em anexo
        $email = new EnviarEmail();
        $email->enviar_pdf_pedido($pedido['pedido']->email, $ficheiro);

        // eliminar o pdf
        unlink(PDF_PATH . $ficheiro);

        Store::redirect('detalhe_pedido&id=' . $_GET['id'], true);
    }

    // ===========================================================
    // OPERAÇÕES APÓS MUDANÇA DE ESTADOv [pedido]
    // ===========================================================

    private function op_notificar_cliente_mudanca_estado($id_pedido)
    {
        // vai enviar um email para o cliente indicando que o pedido sofre alterações
        $admin_model = new AdminModel();
        $pedido = $admin_model->buscar_detalhes_pedido($id_pedido);

        $email = new EnviarEmail();
        $email->email_notificar_cliente_mudanca_estado_pedido($pedido);
    }

    // ===========================================================
    private function op_enviar_email_pedido_enviado($id_pedido)
    {
        // executar as operações para enviar email ao cliente com o pdf do pedido.
        $admin_model = new AdminModel();
        $pedido = $admin_model->buscar_detalhes_pedido($id_pedido);

        // Store::PrintData($pedido);

        $pdf = new PDF();
        $pdf->set_template(getcwd() . '\tamplates\pdf_pedido.pdf');



        //prepara as configurações base do pdf
        $pdf->set_font_family('Courier New');
        $pdf->set_size('10px');
        $pdf->set_cor('#fff');
        $pdf->set_font_weight('bold');


        $pdf->posicao_dimensao(80, 153, 400, 20);
        $pdf->escrever('Data: ' . date('d/m/Y', strtotime($pedido['pedido']->data_pedido)) . ' | Código: ' . $pedido['pedido']->codigo_pedido);

        $pdf->set_size('14px');
        $pdf->set_cor('black');
        $pdf->set_align('right');
        $pdf->set_font_weight('900');

        // nome do cliente
        $pdf->posicao_dimensao(402, 240, 300, 20);
        $pdf->escrever($pedido['pedido']->cliente);

        $pdf->posicao_dimensao(402, 260, 300, 20);
        $telefone = empty($pedido['pedido']->telefone) ? '' : ' - ' . $pedido['pedido']->telefone;
        $pdf->escrever($pedido['pedido']->email . $telefone);

        $pdf->posicao_dimensao(402, 280, 300, 20);
        $pdf->escrever(ucfirst($pedido['pedido']->morada));

        // apresentação da lista de produtos
        $x = 430;
        $total_pedido = 0;
        $pdf->set_font_weight('regular');
        foreach ($pedido['lista_produtos'] as $produto) {

            // localização da apresentação da descrição
            $pdf->set_align('left');
            $pdf->posicao_dimensao(100, $x, 217, 20);
            $pdf->escrever($produto->designacao_item);

            // quantidade
            $pdf->set_align('right');
            $pdf->posicao_dimensao(350, $x, 160, 20);
            $pdf->escrever($produto->quantidade);

            // preço
            $pdf->posicao_dimensao(520, $x, 188, 20);
            $preco = $produto->quantidade * $produto->preco;
            $pdf->escrever(number_format($preco, 2, ',', '.') . ' kz');

            $total_pedido += $preco;
            $x += 28;
        }

        // preço
        $pdf->set_align('right');
        $pdf->set_font_weight('bold');
        $pdf->set_size('19px');
        $pdf->set_cor('white');


        $pdf->posicao_dimensao(450, 727, 263, 28);
        $pdf->escrever('Total: ' . number_format($total_pedido, 2, ',', '.') . 'kz');


        // guardar o pdf
        $ficheiro = 'comprovante_pedido' . date('ymdHis') . '.pdf';
        $pdf->salvar_pdf($ficheiro);


        // enviar email com o ficheiro em anexo
        $email = new EnviarEmail();
        $email->enviar_pdf_pedido($pedido['pedido']->email, $ficheiro);

        // eliminar o pdf
        unlink(PDF_PATH . $ficheiro);
    }

    // ===========================================================
    // CARDAPIO
    // ===========================================================
    public function cardapio()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        $admin_model = new AdminModel();

        // analisa que categoria mostrar
        $c = 'todos';
        if (isset($_GET['c'])) {
            $c = $_GET['c'];
        }

        $cardapio = $admin_model->lista_itens_cardapio($c);
        $lista_categoria = $admin_model->lista_categorias();
        $prato_do_dia = $admin_model->prado_do_dia();

        $dados = [
            'itens_cardapio' => $cardapio,
            'categorias' => $lista_categoria,
            'prato_do_dia' => $prato_do_dia,
            'c' => $c
        ];

        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/pagina_cardapio',
            'admin/layouts/footerHtml'
        ], $dados);
    }

    // ===========================================================
    public function adicionar_novo_item()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }


        // validações
        // validações dos campos 
        $camposObrigatorios = ['prato', 'detalhes', 'categoria', 'preco'];
        $erros = [];

        // Verificar cada campo individualmente
        foreach ($camposObrigatorios as $campo) {
            if (empty($_POST[$campo])) {
                $erros[] = '<span class="my-0 p-0">' . ucfirst(str_replace('_', ' ', $campo)) . " é obrigatório." . '</span>';
            }
        }

        // Se houver erros, armazenar e redirecionar
        if (!empty($erros)) {
            $_SESSION['erro'] = implode('<br>', $erros);
            Store::redirect('cardapio', true);
            return;
        }

        $admin_model = new AdminModel();
        $admin_model->adicionar_novo_item();

        Store::redirect('cardapio', true);
    }

    public function editar_item_cardapio()
    {

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID não fornecido']);
            return;
        }

        $id = intval($_GET['id']);

        $admin_model = new AdminModel();
        $item = $admin_model->buscar_item($id);

        if ($item) {
            header('Content-Type: application/json');
            echo json_encode($item);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Item não encontrado']);
        }
    }

    public function form_editar_item()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }


        // Array ( [id] => 1 [prato] => arroz [detalhes] => arroz de pato com avo [categoria] => novo [preco] => 2500.00 )
        // validações
        // validações dos campos 
        $camposObrigatorios = ['prato', 'detalhes', 'categoria', 'preco'];
        $erros = [];

        // Verificar cada campo individualmente
        foreach ($camposObrigatorios as $campo) {
            if (empty($_POST[$campo])) {
                $erros[] = '<span class="my-0 p-0">' . ucfirst(str_replace('_', ' ', $campo)) . " é obrigatório." . '</span>';
            }
        }

        // Se houver erros, armazenar e redirecionar
        if (!empty($erros)) {
            $_SESSION['erro'] = implode('<br>', $erros);
            Store::redirect('cardapio', true);
            return;
        }

        $admin_model = new AdminModel();
        $admin_model->editar_item_cardapio();

        Store::redirect('cardapio', true);
    }

    public function editar_status_item()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe um id_item na query string
        if (!isset($_GET['id_item'])) {
            Store::redirect('inicio', true);
            return;
        }

        $id_item = Store::aesDesencriptar($_GET['id_item']);
        // verifica se o id_cliente é válido
        if (empty($id_item)) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe uma opção na query string
        if (!isset($_GET['s'])) {
            Store::redirect('inicio', true);
            return;
        }

        $s = $_GET['s'];

        $admin_model = new AdminModel();
        $res = $admin_model->altera_estado_item($s, $id_item);

        if (!$res) {
            $_SESSION['erro'] = 'Ocorreu um erro ao Editar o item do Cardápio';
            Store::redirect('cardapio', true);
            return;
        }

        $_SESSION['alert'] = $res;
        Store::redirect('cardapio', true);
        return;
    }

    public function delete_item_cardapio()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe um id_item na query string
        if (!isset($_GET['id_item'])) {
            Store::redirect('inicio', true);
            return;
        }

        $id_item = Store::aesDesencriptar($_GET['id_item']);
        // verifica se o id_cliente é válido
        if (empty($id_item)) {
            Store::redirect('inicio', true);
            return;
        }

        $admin_model = new AdminModel();
        $admin_model->delete_item_cardapio($id_item);

        $_SESSION['alert'] = 'NOTIFICAÇÃO - Item Removido do cardápio.';
        Store::redirect('cardapio', true);
        return;
    }

    public function op_remover_definir_prato_dia()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe um id_item na query string
        if (!isset($_GET['id_item'])) {
            Store::redirect('inicio', true);
            return;
        }

        $id_item = Store::aesDesencriptar($_GET['id_item']);
        // verifica se o id_cliente é válido
        if (empty($id_item)) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe uma opção na query string
        if (!isset($_GET['acao'])) {
            Store::redirect('inicio', true);
            return;
        }

        $acao = $_GET['acao'];

        $admin_model = new AdminModel();

        $res = $admin_model->op_remover_definir_prato_dia($acao, $id_item);

        if (!$res) {
            $_SESSION['erro'] = 'Ocorreu um erro ao Editar o item do Cardápio';
            Store::redirect('cardapio', true);
            return;
        }

        $_SESSION['alert'] = $res;
        Store::redirect('cardapio', true);
        return;
    }

    // ===========================================================
    // CLIENTE
    // ===========================================================
    public function lista_cliente()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        $admin_model = new AdminModel();
        $lista_clientes = $admin_model->lista_clientes();
        $dados = [
            'clientes' => $lista_clientes
        ];

        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/lista_clientes',
            'admin/layouts/footerHtml'
        ], $dados);
    }

    // ===========================================================
    public function detalhe_cliente()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe um id_cliente na query string
        if (!isset($_GET['c'])) {
            Store::redirect('inicio', true);
            return;
        }

        $id_cliente = Store::aesDesencriptar($_GET['c']);
        // verifica se o id_cliente é válido
        if (empty($id_cliente)) {
            Store::redirect('inicio', true);
            return;
        }

        // buscar id do usuario
        // verifica o se é cliente
        $usuario_model = new Usuario();
        $usuario = $usuario_model->buscar_id_usuario($id_cliente);

        $admin_model = new AdminModel();
        $data = [];
        if (!$usuario) {

            // não tem conta - possivelmente tem reserva
            // 937369832
            $data = [
                'dados_cliente' => $admin_model->dados_pessoa($id_cliente),
                'total_pedidos' => $admin_model->total_pedidos_cliente($id_cliente),
                'total_reserva' => $admin_model->total_reserva_cliente($id_cliente),
                'su' => 'n'
            ];
        } else {

            $id_usuario = $usuario[0]->id_usuario;

            // buscar os dados do cliente
            $data = [
                'dados_cliente' => $admin_model->buscar_cliente($id_usuario),
                'total_pedidos' => $admin_model->total_pedidos_cliente($id_usuario),
                'total_reserva' => $admin_model->total_reserva_cliente($id_cliente),
                'su' => 's'
            ];
        }



        // Store::PrintData($data);
        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/detalhe_cliente',
            'admin/layouts/footerHtml'
        ], $data);
    }

    // ===========================================================
    public function cliente_historico_pedido()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe o id_cliente encriptado
        if (!isset($_GET['c'])) {
            Store::redirect('inicio', true);
        }

        // definir o id_cliente que vem encriptado
        $id_cliente = Store::aesDesencriptar($_GET['c']);
        $ADMIN = new AdminModel();

        $data = [
            'cliente' => $ADMIN->buscar_cliente($id_cliente),
            'lista_pedidos' => $ADMIN->buscar_pedidos_cliente($id_cliente)
        ];

        // Store::PrintData($data);

        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/cliente_historico_pedido',
            'admin/layouts/footerHtml'
        ], $data);
    }

    // ===========================================================
    public function cliente_historico_reserva()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe o id_cliente encriptado
        if (!isset($_GET['c'])) {
            Store::redirect('inicio', true);
        }

        // definir o id_cliente que vem encriptado
        $id_cliente = Store::aesDesencriptar($_GET['c']);
        $ADMIN = new AdminModel();

        $data = [
            'cliente' => $ADMIN->dados_pessoa($id_cliente),
            'lista_reservas' => $ADMIN->buscar_reservas_cliente($id_cliente)
        ];

        // Store::PrintData($data);
        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/cliente_historico_reserva',
            'admin/layouts/footerHtml'
        ], $data);
    }

    public function pagina_mesas()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        $ADMIN = new AdminModel();

        $filtro = '';
        if (isset($_GET['f'])) {
            $filtro = $_GET['f'];
        }

        $data = [
            'lista_mesas' => $ADMIN->mesas($filtro),
            'filtro' => $filtro
        ];

        // Store::PrintData($data);
        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/pagina_mesas',
            'admin/layouts/footerHtml'
        ], $data);
    }

    public function historico_reserva_mesa()
    {

        $numero = isset($_GET['n']) ? $_GET['n'] : null;

        $admin_model = new AdminModel;


        header('Content-Type: application/json');
        echo json_encode([
            'itens' => $admin_model->historico_reserva_mesa($numero)
        ]);
    }

    public function form_adicionar_mesa()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }


        // Array ( [id] => 1 [prato] => arroz [detalhes] => arroz de pato com avo [categoria] => novo [preco] => 2500.00 )
        // validações
        // validações dos campos 
        $camposObrigatorios = ['numero', 'capacidade', 'estado'];
        $erros = [];

        // Verificar cada campo individualmente
        foreach ($camposObrigatorios as $campo) {
            if (empty($_POST[$campo])) {
                $erros[] = '<span class="my-0 p-0">' . ucfirst(str_replace('_', ' ', $campo)) . " é obrigatório." . '</span>';
            }
        }

        // Se houver erros, armazenar e redirecionar
        if (!empty($erros)) {
            $_SESSION['erro'] = implode('<br>', $erros);
            Store::redirect('cardapio', true);
            return;
        }

        $admin_model = new AdminModel();
        $admin_model->adicionar_nova_mesa();

        Store::redirect('gestao_mesas', true);
        $_SESSION['alert'] = 'Nova mesa adicionada!';
    }

    public function editar_buscar_mesa()
    {

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID não fornecido']);
            return;
        }

        $id = intval($_GET['id']);

        $admin_model = new AdminModel();
        $item = $admin_model->detalhe_mesas($id);

        if ($item) {
            header('Content-Type: application/json');
            echo json_encode($item);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Item não encontrado']);
        }
    }

    public function form_editar_mesa()
    {
        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }


        // Array ( [id] => 1 [prato] => arroz [detalhes] => arroz de pato com avo [categoria] => novo [preco] => 2500.00 )
        // validações
        // validações dos campos 
        $camposObrigatorios = ['numero', 'capacidade', 'estado'];
        $erros = [];

        // Verificar cada campo individualmente
        foreach ($camposObrigatorios as $campo) {
            if (empty($_POST[$campo])) {
                $erros[] = '<span class="my-0 p-0">' . ucfirst(str_replace('_', ' ', $campo)) . " é obrigatório." . '</span>';
            }
        }

        // Se houver erros, armazenar e redirecionar
        if (!empty($erros)) {
            $_SESSION['erro'] = implode('<br>', $erros);
            Store::redirect('cardapio', true);
            return;
        }

        $admin_model = new AdminModel();
        $admin_model->form_editar_mesa();

        Store::redirect('gestao_mesas', true);
        $_SESSION['alert'] = 'Mesa Editada!';
    }

    public function delete_mesa()
    {


        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe um id_item na query string
        if (!isset($_GET['id'])) {
            Store::redirect('inicio', true);
            return;
        }

        $id_item = Store::aesDesencriptar($_GET['id']);
        // verifica se o id_cliente é válido
        if (empty($id_item)) {
            Store::redirect('inicio', true);
            return;
        }

        $admin_model = new AdminModel();
        $admin_model->delete_mesa($id_item);

        $_SESSION['alert'] = 'NOTIFICAÇÃO - Mesa Eliminada.';
        Store::redirect('gestao_mesas', true);
        return;
    }

    // ===========================================================
    // USUARIOS
    // ===========================================================
    public function lista_usuarios()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        $admin_model = new AdminModel();
        $lista_usuarios = $admin_model->lista_usuarios();
        $lista_nivel_acesso = $admin_model->lista_tiposUsers();
        $dados = [
            'usuarios' => $lista_usuarios,
            'niveis' => $lista_nivel_acesso
        ];

        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/lista_usuarios',
            'admin/layouts/footerHtml'
        ], $dados);
    }

    public function op_usuario()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verifica se existe um id na query string
        if (!isset($_GET['id'])) {
            Store::redirect('inicio', true);
            return;
        }

        $id_usuario = Store::aesDesencriptar($_GET['id']);
        // verifica se o id_cliente é válido
        if (empty($id_usuario)) {
            Store::redirect('inicio', true);
            return;
        }

        // vefifica se é o id logado 
        if ($_SESSION['admin'] === $id_usuario) {
            Store::redirect('lista_usuarios', true);
            return;
        }

        // verifica se existe uma opção na query string
        if (!isset($_GET['op'])) {
            Store::redirect('inicio', true);
            return;
        }

        $op_usuario = $_GET['op'];
        $admin_model = new AdminModel();

        switch ($op_usuario) {
            case 'alterar_nivel':
                # code...
                $nivel = '';
                $admin_model->op_alterar_nivel($id_usuario, $nivel);
                break;
            case 'desativar':
                # code...
                $admin_model->op_desativar($id_usuario);
                break;
            case 'ativar':
                # code...
                $admin_model->op_ativar($id_usuario);
                break;
            case 'eliminar':
                $admin_model->op_eliminar($id_usuario);
                break;
        }

        Store::redirect('lista_usuarios', true);
        return;
    }

    public function novouser_submit()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }

        // verificar se foi feito o post
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('lista_usuarios', true);
            return;
        }

        // validações
        // validações dos campos 
        $camposObrigatorios = ['nome_completo', 'nome_usuario', 'email', 'telefone', 'endereco', 'tipo_user'];
        $erros = [];

        // Verificar cada campo individualmente
        foreach ($camposObrigatorios as $campo) {
            if (empty($_POST[$campo])) {
                $erros[] = '<span class="my-0 p-0">' . ucfirst(str_replace('_', ' ', $campo)) . " é obrigatório." . '</span>';
            }
        }

        // Se houver erros, armazenar e redirecionar
        if (!empty($erros)) {
            $_SESSION['alert'] = implode('<br>', $erros);
            Store::redirect('lista_usuarios', true);
            return;
        }

        // validar numero do telefone
        if (!Store::validar_numero($_POST['telefone'])) {
            $_SESSION['alert'] = 'Numero de Telefone Inválido';
            Store::redirect('lista_usuarios', true);
            return;
        }

        $usuario = new Usuario();

        if ($usuario->verificarEmail($_POST['email'])) {
            $_SESSION['alert'] = 'Já existe uma conta associado a esse email';
            Store::redirect('lista_usuarios', true);
            return;
        }

        $senha = $usuario->guardar_user();

        $email = new EnviarEmail();

        $email_usuario = strtolower(trim($_POST['email']));

        $resultado = $email->enviar_senha_email($email_usuario, $senha);

        if ($resultado) {
            //CONTA CRIADA COM SOCESSO VIEW
            $_SESSION['alert'] = 'Conta Criada con sucesso';
            Store::redirect('lista_usuarios', true);
            return;
        } else {
            $_SESSION['alert'] = 'Algo deu errado!....';
            Store::redirect('lista_usuarios', true);
            return;
        }
    }

    public function relatorio()
    {

        // verifica se existe um admin logado
        if (!Store::Admin_logado()) {
            Store::redirect('inicio', true);
            return;
        }



        Store::layout_admin([
            'admin/layouts/headerHtml',
            'admin/layouts/sliderbar',
            'admin/relatorio',
            'admin/layouts/footerHtml'
        ]);
    }

    public function buscar_niveis()
    {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID não fornecido']);
            return;
        }

        $id = intval($_GET['id']);

        $admin_model = new AdminModel();
        $item = $admin_model->lista_niveis();

        if ($item) {
            header('Content-Type: application/json');
            echo json_encode($item);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Item não encontrado']);
        }
    }

    public function carregar_total_reserva()
    {

        $reserva = new ModelReserva();
        $total_item = count($reserva->lista_reservas('', null, ''));


        echo $total_item;
    }
    public function total_pedido_local()
    {

        $admin_model = new AdminModel();

        $dados = $admin_model->total_pedidos()['total_pedido_local'];
        
        echo $dados;
    }

    // $admin_model->total_pedidos()['total_pedido_encomenda']
    public function total_pedido_encomenda()
    {

        $admin_model = new AdminModel();

        $dados = $admin_model->total_pedidos()['total_pedido_encomenda'];
        
        echo $dados;
    }
}
