
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>Detalhe do cliente</h3>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>

            </div>


        </div>
    </div>


    <div class="col-md-12">


        <div class="container">
            <div class="row mt-3">
                <!-- nome completo -->
                <div class="col-3 text-end fw-bold">Nome completo:</div>
                <div class="col-9"><?= $dados_cliente->nome_completo ?></div>
                <!-- morada -->
                <div class="col-3 text-end fw-bold">Endereço:</div>
                <div class="col-9"><?= $dados_cliente->endereco ?></div>
                <!-- telefone -->
                <div class="col-3 text-end fw-bold">Telefone:</div>
                <div class="col-9"><?= empty($dados_cliente->telefone) ? '-' : $dados_cliente->telefone ?></div>
                <!-- email -->
                <div class="col-3 text-end fw-bold">Email:</div>
                <div class="col-9"><a href="mailto:<?= $dados_cliente->email ?>"><?= $dados_cliente->email ?></a></div>
                <!-- idade -->
                <div class="col-3 text-end fw-bold">Idade:</div>
                
                <div class="col-9"><?= $dados_cliente->data_nasc ?></div>
                <!-- ativo -->

                <?php if($su == 's'):?>
                    <div class="col-3 text-end fw-bold">Estado:</div>
                <div class="col-9"><?= $dados_cliente->ativo == 0 ? '<span class="text-danger">Inativo</span>' : '<span class="text-success">Ativo</span>' ?></div>
                <!-- criado em -->
                <div class="col-3 text-end fw-bold">Cliente desde:</div>
                <?php
                $data = DateTime::createFromFormat('Y-m-d H:i:s', $dados_cliente->created_at);
                ?>
                <div class="col-9"><?= $data->format('d-m-Y') ?></div>
            </div>

            <div class="row mt-3">
                <div class="col-9 offset-3">
                    <?php if ($total_pedidos == 0) : ?>
                        <div class="col">
                            <p class="text-muted">Não existem pedidos deste cliente.</p>
                        </div>
                    <?php else : ?>
                        <a href="?a=cliente_historico_pedido&c=<?= \core\classes\Store::aesEcriptar($dados_cliente->id_usuario) ?>" class="">Ver histórico de pedidos...</a>
                    <?php endif; ?>
                </div>
                <?php else:?>
                <?php endif;?>
                
                <div class="col-9 offset-3 mt-2">
                    <?php if ($total_reserva == 0) : ?>
                        <div class="col">
                            <p class="text-muted">Não existem reserva deste cliente.</p>
                        </div>
                    <?php else : ?>
                        <a href="?a=cliente_historico_reserva&c=<?= \core\classes\Store::aesEcriptar($dados_cliente->id_pessoa) ?>" class="">Ver histórico de reservas...</a>
                    <?php endif; ?>
                </div>
            </div>



        </div>
    </div>
</main>