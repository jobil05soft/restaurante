<?php

namespace core\controllers;

use core\classes\Database;
use core\classes\EnviarEmail;
use core\Classes\Store;
use core\Models\Cardapio;
use core\Models\ModelReserva;
use core\Models\Pedido;
use core\Models\Usuario;

class Carrinho
{

    // ===========================================================
    public function adicionar_carrinho()
    {
        // vai buscar o id_item à query string
        if (!isset($_GET['id_item'])) {
            echo isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : '';
            return;
        }

        // define o id do item
        $id_item = $_GET['id_item'];


        // adiciona/gestão da variável de SESSAO do carrinho
        $carrinho = [];

        if (isset($_SESSION['carrinho'])) {
            $carrinho = $_SESSION['carrinho'];
        }

        // adicionar o prato ao carrinho
        if (key_exists($id_item, $carrinho)) {

            // já existe o item. Acrescenta mais uma unidade
            $carrinho[$id_item]++;
        } else {

            // adicionar novo produto ao carrinho
            $carrinho[$id_item] = 1;
        }

        // atualiza os dados do carrinho na sessão
        $_SESSION['carrinho'] = $carrinho;

        // devolve a resposta (número de produtos do carrinho)
        $total_item = 0;
        foreach ($carrinho as $quantidade) {
            $total_item += $quantidade;
        }
        echo $total_item;
    }

    // ===========================================================
    public function remover_item_carrinho()
    {

        // vai buscar o id_produto na query string
        $id_item = Store::aesDesencriptar($_GET['id_item']);

        // buscar o carrinho à sessão
        $carrinho = $_SESSION['carrinho'];

        // remover o produto do carrinho
        unset($carrinho[$id_item]);

        // atualizar o carrinho na sessão
        $_SESSION['carrinho'] = $carrinho;

        // apresentar novamente a página do carrinho
        $this->carrinho();
    }

    // ===========================================================
    public function carrinho()
    {
        // verificar se existe carrinho
        if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0) {
            $dados = [
                'carrinho' => null
            ];
        } else {

            $ids = [];
            foreach ($_SESSION['carrinho'] as $id_item => $quantidade) {
                array_push($ids, $id_item);
            }
            $ids = implode(",", $ids);
            $cardapio = new Cardapio();
            $resultados = $cardapio->buscar_produtos_por_ids($ids);

            // Store::PrintData($resultados);

            $dados_tmp = [];
            foreach ($_SESSION['carrinho'] as $id_item => $quantidade_carrinho) {

                // imagem do produto
                foreach ($resultados as $produto) {
                    if ($produto->id_item == $id_item) {
                        $id_item = $produto->id_item;
                        $imagem = $produto->imagem;
                        $titulo = $produto->nome_item;
                        $quantidade = $quantidade_carrinho;
                        $preco = $produto->preco * $quantidade;

                        // colocar o produto na coleção
                        array_push($dados_tmp, [
                            'id_item' => $id_item,
                            'imagem' => $imagem,
                            'titulo' => $titulo,
                            'quantidade' => $quantidade,
                            'preco' => $preco
                        ]);

                        break;
                    }
                }
            }

            // calcular o total
            $total_do_pedido = 0;
            foreach ($dados_tmp as $item) {
                $total_do_pedido += $item['preco'];
            }
            array_push($dados_tmp, $total_do_pedido);

            // colocar o preço total na sessao
            $_SESSION['total_pedido'] = $total_do_pedido;

            $dados = [
                'carrinho' => $dados_tmp
            ];
        }

        // apresenta a página do carrinho
        Store::Layout([
            'layouts/headerHtml',
            'layouts/nav',
            'carrinho',
            'layouts/footer',
            'layouts/footerHtml',
        ], $dados);
    }

    // ===========================================================
    public function limpar_carrinho()
    {

        // limpa o carrinho de todos os produtos
        unset($_SESSION['carrinho']);

        // refrescar a página do carrinho
        $this->carrinho();
    }

    // ===========================================================
    public function finalizar_pedido()
    {
        // verifica se existe cliente logado
        if (!isset($_SESSION['usuario'])) {

            // coloca na sessão um referrer temporário
            $_SESSION['tmp_carrinho'] = true;

            // redirecionar para o quadro de login
            Store::redirect('login');
        } else {

            Store::redirect('finalizar_pedido_resumo');
        }
    }

    // ===========================================================
    public function finalizar_pedido_resumo()
    {

        // verifica se existe cliente logado
        if (!Store::logado()) {
            Store::redirect('inicio');
        }

        // verifica se pode avançar para a gravação do pedido
        if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0) {
            Store::redirect('inicio');
            return;
        }

        // -------------------------------------------------------
        // informações do carrinho
        $ids = [];
        foreach ($_SESSION['carrinho'] as $id_item => $quantidade) {
            array_push($ids, $id_item);
        }

        $ids = implode(",", $ids);
        $cardapio = new Cardapio();
        $resultados = $cardapio->buscar_produtos_por_ids($ids);

        $dados_tmp = [];
        foreach ($_SESSION['carrinho'] as $id_item => $quantidade_carrinho) {

            // imagem do produto
            foreach ($resultados as $produto) {
                if ($produto->id_item == $id_item) {
                    $id_item = $produto->id_item;
                    $imagem = $produto->imagem;
                    $titulo = $produto->nome_item;
                    $quantidade = $quantidade_carrinho;
                    $preco = $produto->preco * $quantidade;

                    // colocar o produto na coleção
                    array_push($dados_tmp, [
                        'id_item' => $id_item,
                        'imagem' => $imagem,
                        'titulo' => $titulo,
                        'quantidade' => $quantidade,
                        'preco' => $preco
                    ]);

                    break;
                }
            }
        }


        // calcular o total
        $total_do_pedido = 0;
        foreach ($dados_tmp as $item) {
            $total_do_pedido += $item['preco'];
        }
        array_push($dados_tmp, $total_do_pedido);

        // preparar os dados da view
        $dados = [];
        $dados['carrinho'] = $dados_tmp;

        // -------------------------------------------------------
        // buscar informações do cliente
        $cliente = new Usuario();
        $dados_cliente = $cliente->buscar_dados($_SESSION['user']);
        $dados['cliente'] = $dados_cliente;

        // Store::PrintData($dados_cliente);
        // -------------------------------------------------------
        // gerar o código da encomenda
        if (!isset($_SESSION['codigo_pedido'])) {
            $codigo_pedido = Store::gerarCodigo();
            $_SESSION['codigo_pedido'] = $codigo_pedido;
        }


        // numeros de mesa
        $model = new Pedido();
        $dados['mesas'] = $model->mesas();

        // apresenta a página do carrinho
        Store::Layout([
            'layouts/headerHtml',
            'layouts/nav',
            'pedido_resumo',
            'layouts/footer',
            'layouts/footerHtml',
        ], $dados);
    }

    public function morada_alternativa()
    {

        // receber os dados via AJAX(axios)
        $post = json_decode(file_get_contents('php://input'), true);

        // adiciona ou altera na sessão a variável (coleção / array) dados_alternativos
        $_SESSION['dados_alternativos'] = [
            'morada' => $post['text_morada'],
            'email' => $post['text_email'],
            'telefone' => $post['text_telefone'],
            'mesa' => $post['text_mesa'],
        ];
    }
    // ===========================================================
    public function confirmar_pedido()
    {

        // Store::PrintData($_SESSION['dados_alternativos']);

        // verifica se existe usuario logado
        if (!Store::logado()) {
            Store::redirect('inicio');
        }

        // verifica se pode avançar para a gravação da encomenda
        if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0) {
            Store::redirect('inicio');
            return;
        }

        // ---------------------------------------
        // enviar email para o cliente com os dados da encomenda e pagamento
        $dados_pedido = [];

        // buscar os dados dos produtos
        $ids = [];
        foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {
            array_push($ids, $id_produto);
        }
        $ids = implode(",", $ids);
        $produtos = new Cardapio();
        $itens_do_pedido = $produtos->buscar_produtos_por_ids($ids);

        // estrutura dos dados dos produtos
        $string_produtos = [];

        foreach ($itens_do_pedido as $resultado) {

            // quantidade
            $quantidade = $_SESSION['carrinho'][$resultado->id_item];

            // string do produto
            $string_produtos[] = "$quantidade x $resultado->nome_item - " . number_format($resultado->preco, 2, ',', '.') . 'kz / Uni.';
        }

        // lista de produtos para o email
        $dados_pedido['lista_produtos'] = $string_produtos;

        // preco total da encomenda para o email
        $dados_pedido['total'] = number_format($_SESSION['total_pedido'], 2, ',', '.') . '$';

        // dados de pagamento
        $dados_pedido['dados_pagamento'] = [
            'numero_da_conta' => '123456789',
            'codigo_pedido' => $_SESSION['codigo_pedido'],
            'total' => number_format($_SESSION['total_pedido'], 2, ',', '.') . '$'
        ];

        // // enviar o email para o cliente com os dados do pedido
        $email = new EnviarEmail();
        $resultado = $email->enviar_email_confirmacao_pedido($_SESSION['usuario'], $dados_pedido);

        // ---------------------------------------
        // guardar na base de dados a encomenda

        $dados_pedido = [];

        $dados_pedido['mesa'] = null;
        // veifica a modalidade do pedido
        if (isset($_GET['m'])) {

            switch ($_GET['m']) {
                case 'local':
                    $dados_pedido['modalidade'] = 'no local';

                    // defina o numero da mesa
                    $dados_pedido['mesa'] = $_SESSION['dados_alternativos']['mesa'];
                    break;
                case 'fora':
                    $dados_pedido['modalidade'] = 'em casa';
                    break;
            }
        } else {
            $dados_pedido['modalidade'] = 'no local';
        }

        $dados_pedido['id_usuario'] = $_SESSION['user'];
        // morada
        if (isset($_SESSION['dados_alternativos']['morada']) && !empty($_SESSION['dados_alternativos']['morada'])) {

            // considerar a morada alternativa
            $dados_pedido['morada'] = $_SESSION['dados_alternativos']['morada'];
            $dados_pedido['email'] = $_SESSION['dados_alternativos']['email'];
            $dados_pedido['telefone'] = $_SESSION['dados_alternativos']['telefone'];
        } else {

            // considerar a endereços do cliente na base de dados
            $cliente = new Usuario();
            $dados_cliente = $cliente->buscar_dados($_SESSION['user']);

            $dados_pedido['morada'] = $dados_cliente->endereco;
            $dados_pedido['email'] = $dados_cliente->email;
            $dados_pedido['telefone'] = $dados_cliente->telefone;
        }

        // codigo do pedido
        $dados_pedido['codigo_pedido'] = $_SESSION['codigo_pedido'];

        // status
        $dados_pedido['status'] = 'AGUARDANDO PAGAMENTO';
        $dados_pedido['mensagem'] = '';

        // -----------------------------------
        // dados dos produtos da encomenda
        // $produtos_da_encomenda (nome_produto, preco)
        $dados_produtos = [];
        foreach ($itens_do_pedido as $produto) {
            $dados_produtos[] = [
                'id_item' => $produto->id_item,
                'designacao_item' => $produto->nome_item,
                'preco_unidade' => $produto->preco,
                'quantidade' => $_SESSION['carrinho'][$produto->id_item]
            ];
        }

        // Store::PrintData($dados_pedido, false);
        // Store::PrintData($dados_produtos);

        $pedido = new Pedido();
        $pedido->guardar_pedido($dados_pedido, $dados_produtos);

        // preparar dados para apresentar na página de agradecimento
        $codigo_pedido = $_SESSION['codigo_pedido'];
        $total_pedido = $_SESSION['total_pedido'];

        // ---------------------------------------
        // limpar todos os dados da encomenda que estão no carrinho
        unset($_SESSION['codigo_pedido']);
        unset($_SESSION['carrinho']);
        unset($_SESSION['total_pedido']);
        unset($_SESSION['dados_alternativos']);

        // ---------------------------------------
        // apresenta a página a agradecer a encomenda
        $dados = [
            'codigo_pedido' => $codigo_pedido,
            'total_pedido' => $total_pedido,
            'tipo_pedido' => $dados_pedido['modalidade']
        ];


        Store::Layout([
            'layouts/headerHtml',
            'layouts/nav',
            'pedido_confirmado',
            'layouts/footer',
            'layouts/footerHtml',
        ], $dados);
    }
}
