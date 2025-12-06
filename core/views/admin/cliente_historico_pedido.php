<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>Lista Pedidos Cliente</h3>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>

            </div>


        </div>
    </div>

    <div class="row">
        <div class="col">Nome: <strong><?= $cliente->nome_completo ?></strong></div>
        <div class="col">Email: <strong><?= $cliente->email ?></strong></div>
        <div class="col">Telefone: <strong><?= $cliente->telefone ?></strong></div>
    </div>

    <hr>

    <div class="col-md-12">


        <div class="container">

            <div class="row">
                <div class="table-responsive small">
                    <?php if (count($lista_pedidos) == 0) : ?>
                        <hr>
                        <p>Não existem pedidos registadas.</p>
                        <hr>
                    <?php else : ?>
                        <small>
                            <table class="table table-striped" id="tabela">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Data</th>
                                        <th>Morada</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Código</th>
                                        <th>Status</th>
                                        <th>Atualizada em</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($lista_pedidos as $pedido) : ?>
                                        <tr>
                                            <td><?= $pedido->data_pedido ?></td>
                                            <td><?= $pedido->morada ?></td>
                                            <td><?= $pedido->email ?></td>
                                            <td><?= $pedido->telefone ?></td>
                                            <td><?= $pedido->codigo_pedido ?></td>
                                            <td><?= $pedido->status ?></td>
                                            <td><?= $pedido->updated_at ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </small>
                    <?php endif; ?>
                </div>


            </div>
           
        </div>
    </div>
</main>