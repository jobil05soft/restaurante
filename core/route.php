<?php

$route = [

    'test' => 'main@email_teste',
    'inicio' => 'main@index',
    'cardapio' => 'main@cardapio',

    'carregar_cardapio' => 'main@carregar_itens_cardapio',
    'termos_condicoes' => 'main@termos_condicoes',

    // Novo usuario
    'criar_conta' => 'main@criar_conta',
    'criar_conta_submit' => 'main@registrar_usuario',
    'confirmar_email' => 'main@confirmar_email',

    // Login
    'login' => 'main@login',
    'login_submit' => 'main@login_submit',
    'logout' => 'main@logout',

    // Pagina usuario
    'painel_usuario' => 'main@pagina_usuario',
    'perfil' => 'main@perfil',

    'editar_perfil' => 'main@editar_perfil',
    'editar_perfil_submit' => 'main@editar_perfil_submit',
    'alterar_senha' => 'main@alterar_senha',
    'alterar_senha_submit' => 'main@alterar_senha_submit',

    'historico_reservas' => 'main@historico_reservas',
    'detalhe_reserva' => 'main@detalhe_reserva',
    'submeter_pagamento_reserva' => 'main@submeter_pagamento_reserva',
    
    'cancelar_reserva_usuario' => 'reserva@cancelar_reserva_usuario',
    'pdf_reserva' => 'reserva@pdf_reserva',

    'historico_pedidos' => 'main@historico_pedidos',
    'detalhe_pedido' => 'main@historico_pedidos_detalhe',

    'submeter_pagamento_pedido' => 'main@submeter_pagamento_pedido',

    // Reservar Mesa
    'reservar_mesa' => 'reserva@reserva_mesa',
    'reservar_mesa_submit' => 'reserva@reservar_mesa_submit',

    'finalizar_reserva' => 'reserva@finalizar_reserva',
    'reserva_resumo' => 'reserva@reserva_resumo',

    'cancelar_reserva' => 'reserva@cancelar_reserva',
    'confirmar_reserva' => 'reserva@confirmar_reserva',

    // carrinho
    'carrinho' => 'carrinho@carrinho',
    'adicionar_carrinho' => 'carrinho@adicionar_carrinho',
    'limpar_carrinho' => 'carrinho@limpar_carrinho',
    'remover_item_carrinho' => 'carrinho@remover_item_carrinho',

    'finalizar_pedido' => 'carrinho@finalizar_pedido',
    'finalizar_pedido_resumo' => 'carrinho@finalizar_pedido_resumo',

    'morada_alternativa' => 'carrinho@morada_alternativa',
    'confirmar_pedido' => 'carrinho@confirmar_pedido'

];

// defina ação por padrão
$acao = 'inicio';

// Verificar se existe uma ação na query string
if (isset($_GET['a'])) {

    // verifica se aacao existe nas routas
    if (!key_exists($_GET['a'], $route)) {
        $acao = 'inicio';
    } else {
        $acao = $_GET['a'];
    }
}

// trata a definição das rotas
$partes = explode('@', $route[$acao]);
$controller = 'core\\controllers\\' . ucfirst($partes[0]);
$metodo = $partes[1];

// instancia do metodo
$controlador = new $controller();
$controlador->$metodo();
