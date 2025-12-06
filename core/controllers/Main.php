<?php

namespace core\controllers;

use core\classes\EnviarEmail;
use core\Classes\Store;
use core\Models\Cardapio;
use core\Models\ModelReserva;
use core\Models\Pedido;
use core\Models\Usuario;

class Main
{
    // ============================================================
    public function index()
    {
        $c = 'todos';
        //carrega o cardapio
        $cardapio = new Cardapio();
        $itens_cardapio = $cardapio->all($c);
        $categorias = $cardapio->lista_categorias();

        $data = [
            'itens_cardapio' => $itens_cardapio,
            'categorias' => $categorias
        ];

        // Carrega a pagina inicial
        Store::Layout([
            'layouts/headerHtml',
            'layouts/nav',
            'home',
            'layouts/footer',
            'layouts/footerHtml',
        ], $data);
    }

    // ============================================================
    public function cardapio()
    {

        // analisa que categoria mostrar
        $c = 'todos';
        if (isset($_GET['c'])) {
            $c = $_GET['c'];
        }

        //carrega o cardapio
        $cardapio = new Cardapio();
        $itens_cardapio = $cardapio->all($c);
        $categorias = $cardapio->lista_categorias();

        $data = [
            'itens_cardapio' => $itens_cardapio,
            'categorias' => $categorias
        ];

        Store::Layout([
            'layouts/headerHtml',
            'layouts/nav',
            'cardapio',
            'layouts/footer',
            'layouts/footerHtml',
        ], $data);
    }

    // ============================================================
    public function carregar_itens_cardapio()
    {
        $model = new Cardapio();

        // Verifica se foi passada uma categoria
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

        if ($categoria) {
            $itens = $model->all($categoria);
        } else {
            $itens = $model->all('todos');
        }

        // Pega todas as categorias para o topo
        $todas_categorias = $model->lista_categorias();

        header('Content-Type: application/json');
        echo json_encode([
            'itens' => $itens,
            'categorias' => $todas_categorias
        ]);
    }

    // ============================================================
    public function termos_condicoes()
    {

        // Carrega a pagina inicial
        Store::Layout([
            'layouts/headerHtml',
            'layouts/nav',
            'termos_condicoes',
            'layouts/footer',
            'layouts/footerHtml',
        ]);
    }




    // ============================================================
    public function criar_conta()
    {

        if (Store::logado()) {
            Store::redirect('inicio');
            return;
        }

        Store::Layout([
            'layouts/headerHtml',
            'layouts/nav',
            'novo_usuario',
            'layouts/rodape',
            'layouts/footerHtml',
        ]);
    }

    // ============================================================
    public function registrar_usuario()
    {

        // Verificar se tem usuário logado
        if (Store::logado()) {
            Store::redirect('inicio');
            return;
        }

        // verificar se foi feito o post
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('criar_conta');
            return;
        }

        // validações dos campos 
        $camposObrigatorios = ['nome', 'email', 'senha_1', 'senha_2', 'telefone', 'morada'];
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
            $this->criar_conta();
            return;
        }

        // validar numero do telefone
        if (!Store::validar_numero($_POST['telefone'])) {
            $_SESSION['mensagem'] = 'Numero de Telefone Inválido';
            $this->criar_conta();
            return;
        }

        // verifica se a senha 1 é igual a senha 2
        if ($_POST['senha_1'] !== $_POST['senha_2']) {
            $_SESSION['mensagem'] = 'As senha não são iguais';
            $this->criar_conta();
            return;
        }

        // Verifica se existe uma usuario na base de dados com o mesmo email
        $usuario = new Usuario();
        if ($usuario->verificarEmail($_POST['email'])) {
            $_SESSION['mensagem'] = 'Já existe uma conta associado a esse email';
            $this->criar_conta();
            return;
        }

        // guardar usuario na base de dados
        // devolver o purl
        $purl = $usuario->salvarUsuario();

        $email = new EnviarEmail();

        $email_usuario = strtolower(trim($_POST['email']));

        $resultado = $email->enviar_email_confirmacao($email_usuario, $purl);

        if ($resultado) {
            //CONTA CRIADA COM SOCESSO VIEW
            Store::layout([
                'layouts/headerHtml',
                'layouts/nav',
                'conta_criada',
                'layouts/footer',
                'layouts/footerHtml'
            ]);
        } else {
            Store::layout([
                'layouts/headerHtml',
                'layouts/nav',
                'error',
                'layouts/rodape',
                'layouts/footerHtml'
            ]);
        }
    }

    // ============================================================
    public function confirmar_email()
    {

        //Verifica se ja existe sessão
        if (Store::logado()) {
            $this->index();
            return;
        }

        // verificar se existe na query string um purl
        if (!isset($_GET['purl'])) {
            $this->index();
            return;
        }

        $purl = $_GET['purl'];

        // verifica se o purl é válido
        if (strlen($purl) != 12) {
            $this->index();
            return;
        }

        $usuario = new Usuario();
        $resultado = $usuario->validar_email($purl);

        if ($resultado) {
            Store::layout([
                'layouts/headerHtml',
                'layouts/nav',
                'conta_confirmada_sucesso',
                'layouts/footer',
                'layouts/footerHtml'
            ]);
        } else {
            Store::layout([
                'layouts/headerHtml',
                'layouts/nav',
                'error',
                'layouts/footer',
                'layouts/footerHtml'
            ]);
        }
    }
    // ============================================================
    public function login()
    {

        if (Store::logado()) {
            Store::redirect('inicio');
            return;
        }

        Store::layout([
            'layouts/headerHtml',
            'layouts/nav',
            'login',
            'layouts/footer',
            'layouts/footerHtml',
        ]);
    }

    // ============================================================
    public function login_submit()
    {

        // Verificar se tem usuário logado
        if (Store::logado()) {
            Store::redirect('inicio');
            return;
        }

        // verificar se foi feito o post
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('criar_conta');
            return;
        }

        //Verificar se os campos vieram perfeitamente preenchidos
        if (
            !isset($_POST['email']) ||
            !isset($_POST['senha'])
        ) {
            // erro de preenchimento de formulario
            $_SESSION['mensagem'] = 'Login Invalido';
            Store::redirect('login');
            return;
        }

        //prepara os dados para o model
        $usuario = trim(strtolower($_POST['email']));
        $senha = trim($_POST['senha']);

        // verificar se o usuario entra com emial ou username
        if (filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
            $sql_condition = "p.email = '$usuario'";
        } else {
            $sql_condition = "u.nome = '$usuario'";
        }

        //carrega o model e verifica se o login é valido
        $user = new Usuario();
        $resultado = $user->validar_login($sql_condition, $senha);

        //analise o resultado
        if (is_bool($resultado)) {

            //Login invalido
            $_SESSION['mensagem'] = 'Login Invalido';
            Store::redirect('login');
            return;
        } else {


            // Login  valido 
            //Coloca os dados do usuario na sessão
            $_SESSION['user'] = $resultado->id_usuario;
            $_SESSION['nome_usuario'] = $resultado->nome_usuario;
            $_SESSION['usuario'] = $resultado->email;


            // redirecionar para o local correto
            if (isset($_SESSION['tmp_carrinho'])) {

                // remove a variável temporária da sessão
                unset($_SESSION['tmp_carrinho']);

                // redireciona para resumo da pedido
                Store::redirect('finalizar_pedido_resumo');
            } elseif (isset($_SESSION['tmp_reserva'])) {

                unset($_SESSION['tmp_reserva']);


                Store::redirect('reserva_resumo');
            } else {

                // redirectionamento para a loja
                Store::redirect('painel_usuario');
            }
        }
    }

    // ============================================================
    public function logout()
    {

        // Verificar se tem usuário logado
        if (!Store::logado()) {
            Store::redirect('inicio');
            return;
        }

        // remover os dados do usuario da sessão
        unset($_SESSION['user']);
        unset($_SESSION['nome_usuario']);
        unset($_SESSION['usuario']);

        // redireciona para o inicio
        Store::redirect('inicio');
    }

    // ============================================================
    public function pagina_usuario()
    {

        if (!Store::logado()) {
            Store::redirect('login');
            return;
        }

        $id = $_SESSION['user'];
        $cliente = new Usuario();
        $id_pessoa = $cliente->buscar_id_pessoa($id);

        $reservas = new ModelReserva();
        $total_reservas_pendentes = $reservas->total_reservas_pendentes($id_pessoa);

        $pedidos = new Pedido();
        $total_pedido_processamento = $pedidos->total_pedido_processamento($id);

        $data = [
            'total_reservas_pendentes' => $total_reservas_pendentes,
            'total_pedido_processamento' => $total_pedido_processamento
        ];

        Store::layout([
            'painel-usuario/layouts/headerHtml',
            'painel-usuario/layouts/sliderbar',
            'painel-usuario/index',
            'painel-usuario/layouts/footerHtml',
        ], $data);
    }

    // ============================================================
    public function perfil()
    {

        if (!Store::logado()) {
            Store::redirect('login');
            return;
        }

        $usuario = new Usuario();
        $dtemp = $usuario->buscar_dados($_SESSION['user']);

        // Store::PrintData($dtemp);
        $dados = [
            'dados_cliente' => [
                'Email' => $dtemp->email,
                'Nome Completo' => $dtemp->nome_completo,
                'Nome de Usuário' => $dtemp->nome_usuario,
                'Telefone' => $dtemp->telefone,
                'Tipo de Usuário' => ucfirst($dtemp->nivel_acesso),
            ]
        ];

        Store::layout([
            'painel-usuario/layouts/headerHtml',
            'painel-usuario/layouts/sliderbar',
            'painel-usuario/perfil',
            'painel-usuario/layouts/footerHtml',
        ], $dados);
    }

    // ============================================================
    public function editar_perfil()
    {

        if (!Store::logado()) {
            Store::redirect('login');
            return;
        }

        $usuario = new Usuario();
        $dtemp = $usuario->buscar_dados($_SESSION['user']);

        $dados = ['dados_pessoais' => $dtemp];
        Store::layout([
            'painel-usuario/layouts/headerHtml',
            'painel-usuario/layouts/sliderbar',
            'painel-usuario/editar_perfil',
            'painel-usuario/layouts/footerHtml',
        ], $dados);
    }

    // ============================================================
    public function editar_perfil_submit()
    {
        //Verifica se ja existe sessão
        if (!Store::logado()) {
            $this->index();
            return;
        }

        //Verifica se existe asubmissão de dados
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('perfil');
            return;
        }

        $nome = trim($_POST['text_nome_completo']);
        $email = strtolower(trim($_POST['text_email']));
        $telefone = trim($_POST['text_telefone']);
        $endereco = trim($_POST['text_morada']);
        $nome_usuario = trim($_POST['text_nome_usuario']);

        //validar dados
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensagem'] = "Endereço de Email invalido";
            Store::redirect('editar_perfil');
            return;
        }

        //vericar se os dados estão vazios
        if (empty($nome)) {
            $_SESSION['mensagem'] = "Preencha devidamento os dados";
            Store::redirect('editar_perfil');
            return;
        }

        $user = new Usuario();
        //Verificar se o email existe noutra conta 
        $existe_noutra_conta = $user->verificar_email_existe_noutra_conta($_SESSION['user'], $email);
        if ($existe_noutra_conta) {
            $_SESSION['mensagem'] = "O Email Já pertence a outra conta";
            Store::redirect('editar_perfil');
            return;
        }


        //telefone
        if (!Store::validar_numero($telefone)) {
            $_SESSION['mensagem'] = "Terninal telefônico incorreto!";
            Store::redirect('editar_perfil');
            return;
        }


        //Actualizar os dados na base de dados
        $user->actualizar_dados_usuario($nome, $email, $telefone, $endereco, $nome_usuario);

        //actualizar os dados do usuario na sessão
        $_SESSION['usuario'] = $email;
        $_SESSION['nome_usuario'] = $nome;

        Store::redirect('perfil');
    }

    // ============================================================
    public function alterar_senha()
    {

        if (!Store::logado()) {
            Store::redirect('login');
            return;
        }

        Store::layout([
            'painel-usuario/layouts/headerHtml',
            'painel-usuario/layouts/sliderbar',
            'painel-usuario/editar_senha',
            'painel-usuario/layouts/footerHtml',
        ]);
    }

    // ============================================================
    public function alterar_senha_submit()
    {


        //Verifica se ja existe sessão
        if (!Store::logado()) {
            $this->index();
            return;
        }

        //Verifica se existe asubmissão de dados
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('perfil');
            return;
        }

        //validar dados
        $senha_antiga = trim($_POST['text_senha_antiga']);
        $nova_senha = trim($_POST['text_senha_nova_1']);
        $confirmar_senha = trim($_POST['text_senha_nova_2']);

        //verificar se a nova senha vem com dados
        if (strlen($nova_senha) < 6) {
            $_SESSION['mensagem'] = "A senha deve ter acima de 6 caracteres.";
            Store::redirect('perfil');
            return;
        }

        //verificar se a nova senha e confirmar senha estão iguais (coensidem)
        if ($nova_senha != $confirmar_senha) {
            $_SESSION['mensagem'] = "As senhas não são iguais";
            Store::redirect('perfil');
            return;
        }

        $user = new Usuario();
        //verificar se senha actual esta correta
        if (!$user->verificar_senha($_SESSION['user'], $senha_antiga)) {
            $_SESSION['mensagem'] = "Senha actual Errada";
            Store::redirect('perfil');
            return;
        }

        //verificar se a nova senha é diferente da senha actual
        if ($nova_senha == $senha_antiga) {
            $_SESSION['mensagem'] = "A nova senha é igual a senha actual";
            Store::redirect('perfil');
            return;
        }

        //Actualizar a nova senha
        $user->actualizar_nova_senha($_SESSION['user'], $nova_senha);
        Store::redirect('perfil');
    }

    // ============================================================
    public function historico_reservas()
    {

        if (!Store::logado()) {
            Store::redirect('login');
            return;
        }


        // buscar todos as reservas associadas a esse usuário
        $modelreserva = new ModelReserva();

        $reservas = $modelreserva->reservas_usuario($_SESSION['user']);
        // Store::PrintData($reservas);
        Store::layout([
            'painel-usuario/layouts/headerHtml',
            'painel-usuario/layouts/sliderbar',
            'painel-usuario/historico_reservas',
            'painel-usuario/layouts/footerHtml',
        ], ['historico_reservas' => $reservas]);
    }

    public function detalhe_reserva()
    {
        if (!Store::logado()) {
            Store::redirect('login');
            return;
        }


        /// vai buscar o id do usuario
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

        // Store::PrintData($reservas);
        Store::layout([
            'painel-usuario/layouts/headerHtml',
            'painel-usuario/layouts/sliderbar',
            'painel-usuario/detalhe_reserva',
            'painel-usuario/layouts/footerHtml',
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


    public function submeter_pagamento_reserva()
    {

        if (!Store::logado()) {
            Store::redirect('login');
            return;
        }

        // verifica se existiu submissão de formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('inicio');
            return;
        }



        $reservas = new ModelReserva();
        $id_reserva = trim($_POST['id_reserva']);

        if (!isset($_FILES['comprovativo']) || $_FILES['comprovativo']['error'] != 0) {
            $_SESSION['erro'] = 'Por favor, envie o comprovativo de pagamento.';
            Store::redirect('detalhe_reserva&id=' . Store::aesEcriptar($id_reserva));
            return;
        }

        // verificar se estas apto para o pagamento
        $reserva = $reservas->dados_reserva($id_reserva);

        if (date('Y-m-d H:i:s') > $reserva->data_limite_pagamento) {
            $_SESSION['erro'] = 'Data para o pagamento expirada.';
            Store::redirect('detalhe_reserva&id=' . Store::aesEcriptar($id_reserva));
            return;
        }


        //1. verificar se existe o upload de imagem

        $ficheiro = '';

        // Store::printData($_FILES);
        if (isset($_FILES)) {
            $formatos = [
                'image/png',
                'image/jpeg',
                'application/pdf'
            ];

            foreach ($_FILES as $file) {

                if (!in_array($file['type'], $formatos)) continue;

                $ficheiro = 'CPR_' . date('ymdhis') . $file['name'];
                move_uploaded_file($file['tmp_name'], CPR . $ficheiro);
            }
        }

        $reservas->salvar_comprovativo_reserva($ficheiro, $id_reserva);

        $_SESSION['alert'] = 'Comprovativo enviado. Aguarde a validação.';
        Store::redirect('detalhe_reserva&id=' . Store::aesEcriptar($id_reserva));
    }

    public function submeter_pagamento_pedido()
    {

        if (!Store::logado()) {
            Store::redirect('login');
            return;
        }

        // verifica se existiu submissão de formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('inicio');
            return;
        }



        $pedidos = new Pedido();
        $id_pedido = trim($_POST['id_pedido']);

        if (!isset($_FILES['comprovativo']) || $_FILES['comprovativo']['error'] != 0) {
            $_SESSION['erro'] = 'Por favor, envie o comprovativo de pagamento.';
            Store::redirect('detalhe_pedido&id=' . Store::aesEcriptar($id_pedido));
            return;
        }

        // verificar se estas apto para o pagamento
        $pedido = $pedidos->detalhes_de_pedido($_SESSION['user'], $id_pedido);


        if (date('Y-m-d H:i:s') > $pedido['dados_pedido']->data_limite) {
            $_SESSION['erro'] = 'Data para o pagamento expirada.';
            Store::redirect('detalhe_pedido&id=' . Store::aesEcriptar($id_pedido));
            return;
        }

        

        //1. verificar se existe o upload de imagem

        $ficheiro = '';

        // Store::printData($_FILES);
        if (isset($_FILES)) {
            $formatos = [
                'image/png',
                'image/jpeg',
                'application/pdf'
            ];

            foreach ($_FILES as $file) {

                if (!in_array($file['type'], $formatos)) continue;

                $ficheiro = 'CPP_' . date('ymdhis') . $file['name'];
                move_uploaded_file($file['tmp_name'], CPP . $ficheiro);
            }
        }

        $pedidos->salvar_comprovativo_pedido($ficheiro, $id_pedido);

        $_SESSION['alert'] = 'Comprovativo enviado. Aguarde a validação.';
        Store::redirect('detalhe_pedido&id=' . Store::aesEcriptar($id_pedido));
    }

    // ============================================================
    public function historico_pedidos()
    {

        if (!Store::logado()) {
            Store::redirect('login');
            return;
        }

        // buscar todos as reservas associadas a esse usuário
        $modelpedido = new Pedido();

        $pedidos = $modelpedido->pedidos_usuario($_SESSION['user']);
        // Store::PrintData($pedidos);

        $dados = [
            'historico_pedidos' => $pedidos
        ];

        Store::layout([
            'painel-usuario/layouts/headerHtml',
            'painel-usuario/layouts/sliderbar',
            'painel-usuario/historico_pedidos',
            'painel-usuario/layouts/footerHtml',
        ], $dados);
    }

    // ============================================================
    public function historico_pedidos_detalhe()
    {

        // ===========================================================
        // verifica se existe um utilizador logado
        if (!Store::logado()) {
            Store::redirect();
            return;
        }

        // verificar se veio indicado um id_pedido (encriptado)
        if (!isset($_GET['id'])) {
            Store::redirect();
            return;
        }

        $id_pedido = null;

        // verifica se o id_pedido é uma string com 32 caracteres
        if (strlen($_GET['id']) != 32) {
            Store::redirect();
            return;
        } else {
            $id_pedido = Store::aesDesencriptar($_GET['id']);
            if (empty($id_pedido)) {
                Store::redirect();
                return;
            }
        }

        // verifica se a pedido pertence a este cliente
        $pedidos = new Pedido();
        $resultado = $pedidos->verificar_pedido_cliente($_SESSION['user'], $id_pedido);

        // Store::PrintData($resultado);
        if (!$resultado) {
            Store::redirect();
            return;
        }

        // vamos buscar os dados de detalhe da pedido.
        $detalhe_pedido = $pedidos->detalhes_de_pedido($_SESSION['user'], $id_pedido);

        $total = 0;
        foreach ($detalhe_pedido['produtos_pedido'] as $produto) {
            $total += ($produto->quantidade * $produto->preco);
        }

        $data = [
            'dados_pedido' => $detalhe_pedido['dados_pedido'],
            'produtos_pedido' => $detalhe_pedido['produtos_pedido'],
            'total_pedido' => $total
        ];

        // vamos apresentar a nova view com esses dados.

        Store::layout([
            'painel-usuario/layouts/headerHtml',
            'painel-usuario/layouts/sliderbar',
            'painel-usuario/historico_pedidos_detalhe',
            'painel-usuario/layouts/footerHtml',
        ], $data);
    }












    // teste
    public function teste_view()
    {

        Store::layout([
            'layouts/headerHtml',
            'layouts/nav',
            'view_custon',
            'layouts/footer',
            'layouts/footerHtml'
        ]);
    }

    // email_teste
    public function email_teste()
    {

        // $email = new EnviarEmail();
        // $email->email_teste();


    }
}
