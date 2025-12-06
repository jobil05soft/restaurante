<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>Lista clientes</h3>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>

            </div>


        </div>
    </div>


    <div class="col-md-12">


        <div class="container">
            <div class="row">

                <div class="table-responsive small">

                    <table class="table table-striped" id="cliente">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th class="text-center">Pedidos</th>
                                <th class="text-center">Usuario? [SIM/NAO]</th>
                                <th class="text-center">Ativo</th>
                                <th class="text-center">Eliminado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>

                                    <td>
                                        <a href="?a=detalhe_cliente&c=<?= \core\classes\Store::aesEcriptar($cliente->id_pessoa) ?>"><?= $cliente->nome_completo ?></a>
                                        <!-- <?php if ($cliente->id_usuario != Null): ?>
                                                <a href="?a=detalhe_cliente&c=<?= \core\classes\Store::aesEcriptar($cliente->id_pessoa) ?>"><?= $cliente->nome_completo ?></a>
                                            <?php else: ?>
                                                <?= $cliente->nome_completo ?>
                                            <?php endif; ?> -->
                                    </td>

                                    <td><?= $cliente->email ?></td>
                                    <td><?= $cliente->telefone ?></td>

                                    <td class="text-center">
                                        <?php if ($cliente->total_pedido == 0): ?>
                                            -
                                        <?php else: ?>
                                            <a href="?a=lista_pedidos&c=<?= \core\classes\Store::aesEcriptar($cliente->id_usuario) ?>"><?= $cliente->total_pedido ?></a>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($cliente->id_usuario != Null): ?>
                                            SIM
                                        <?php else: ?>
                                            NÃO
                                        <?php endif; ?>
                                    </td>


                                    <!-- ativo -->
                                    <td class="text-center">
                                        <?php if ($cliente->ativo == 1): ?>
                                            <i class="text-success fas fa-check-circle"></i></span>
                                        <?php else: ?>
                                            <i class="text-danger fas fa-times-circle"></i></span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- eliminado -->
                                    <td class="text-center">
                                        <?php if ($cliente->deleted_at == null): ?>
                                            <i class="text-danger fas fa-times-circle"></i></span>
                                        <?php else: ?>
                                            <i class="text-success fas fa-check-circle"></i></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>


                </div>
            </div>
            
        </div>
    </div>
</main>