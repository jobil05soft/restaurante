<?php

$route = [

    'inicio' => 'admin@index',
    'admin_login' => 'admin@admin_login',
    'admin_login_submit' => 'admin@admin_login_submit',
    'logout_admin' => 'admin@logout_admin',
    'perfil_admin' => 'admin@perfil_admin',

    // RESERVAS
    'lista_reservas' => 'admin@lista_reservas',
    'detalhe_reserva' => 'admin@detalhe_reserva',
    'reserva_alterar_estado' => 'admin@reserva_alterar_estado',
    'criar_pdf_reserva' => 'admin@criar_pdf_reserva',
    'enviar_pdf_reserva' => 'admin@enviar_pdf_reserva',
    'reserva_alterar_presenca' => 'admin@reserva_alterar_presenca',
    'ver_anexo' => 'admin@ver_anexo',
    'ver_anexo_pedido' => 'admin@ver_anexo_pedido',
    'carregar_total_reserva' => 'admin@carregar_total_reserva',

    // PEDIDOS
    'lista_pedidos' => 'admin@lista_pedidos',
    'pedidos_instantaneos' => 'admin@pedidos_instantaneos',
    'detalhe_pedido' => 'admin@detalhe_pedido',
    'pedido_alterar_estado' => 'admin@pedido_alterar_estado',
    'criar_pdf_pedido' => 'admin@criar_pdf_pedido',
    'enviar_pdf_pedido' => 'admin@enviar_pdf_pedido',
    'carregar_total_pedido' => 'admin@total_pedido_local',
    'total_pedido_encomenda' => 'admin@total_pedido_encomenda',

    // CAEDAPIO
    'cardapio' => 'admin@cardapio',
    'adicionar_novo_item' => 'admin@adicionar_novo_item',
    'editar' => 'admin@editar_item_cardapio',
    'form_editar_item' => 'admin@form_editar_item',
    'editar_status_item' => 'admin@editar_status_item',
    'delete_item_cardapio' => 'admin@delete_item_cardapio',
    'prato_dia' => 'admin@op_remover_definir_prato_dia',

    // MESAS
    'gestao_mesas' => 'admin@pagina_mesas',
    'historico_reserva_mesa' => 'admin@historico_reserva_mesa',
    'form_adicionar_mesa' => 'admin@form_adicionar_mesa',
    'editar_buscar_mesa' => 'admin@editar_buscar_mesa',
    'form_editar_mesa' => 'admin@form_editar_mesa',
    'delete_mesa' => 'admin@delete_mesa',


    // CLIENTE
    'lista_cliente' => 'admin@lista_cliente',
    'detalhe_cliente' => 'admin@detalhe_cliente',
    'cliente_historico_pedido' => 'admin@cliente_historico_pedido',
    'cliente_historico_reserva' => 'admin@cliente_historico_reserva',

    // LISTA DE USUARIO
    'lista_usuarios' => 'admin@lista_usuarios',
    'op_usuario' => 'admin@op_usuario',
    'novouser_submit' => 'admin@novouser_submit',

    'buscar_niveis' => 'admin@buscar_niveis',

    'alter_pass' => 'admin@alter_pass',
    'alter_dados' => 'admin@alter_dados',


    // RELATORIOS
    'relatorios' => 'admin@relatorio',

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
