<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>Lista de Pedidos <small><?= $filtro != '' ? $filtro : '' ?></small></h3>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="?a=lista_pedidos" type="button" class="btn btn-sm btn-outline-secondary">Pedidos Por Encomenda</a>
                <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>

            </div>


        </div>
    </div>


    <div class="col-md-12">



        <div class="row">
            <div class="col">
                <a href="?a=pedidos_instantaneos" class="btn btn-primary btn-sm">Ver todos pedidos</a>
            </div>
            <div class="col">
                <?php
                $f = '';
                if (isset($_GET['f'])) {
                    $f = $_GET['f'];
                }
                ?>

                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-4 text-end col-form-label">Escolher estado:</label>
                    <div class="col-sm-8">
                        <select id="combo-status" class="form-control" onchange="definir_filtro()">
                            <option value="" <?= $f == '' ? 'selected' : '' ?>></option>
                            <option value="pendente" <?= $f == 'pendente' ? 'selected' : '' ?>>Pendentes</option>
                            <option value="em_processamento" <?= $f == 'em_processamento' ? 'selected' : '' ?>>Em processamento</option>
                            <option value="confirmada" <?= $f == 'confirmada' ? 'selected' : '' ?>>Confirmadas</option>
                            <option value="enviada" <?= $f == 'enviada' ? 'selected' : '' ?>>Enviadas</option>
                            <option value="cancelada" <?= $f == 'cancelada' ? 'selected' : '' ?>>Canceladas</option>
                            <option value="concluida" <?= $f == 'concluida' ? 'selected' : '' ?>>Concluídas</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="container">
            <div class="row">

                <div class="table-responsive small">
                    <?php if (count($lista_pedidos) == 0) : ?>
                        <hr>
                        <p>Não existem pedidos registadas.</p>
                        <hr>
                    <?php else : ?>

                        <table class="table table-striped" id="tabela">
                            <thead class="table-dark">
                                <tr>
                                    <th>Data</th>
                                    <th>Código</th>
                                    <th>Nome Cliente</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Status</th>
                                    <th>Atualizado em</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($lista_pedidos as $pedido) : ?>
                                    <tr>
                                        <td><?= $pedido->data_pedido ?></td>
                                        <td><?= $pedido->codigo_pedido ?></td>
                                        <td><?= $pedido->nome_completo ?></td>
                                        <td><?= $pedido->email ?></td>
                                        <td><?= $pedido->telefone ?></td>
                                        <td>
                                            <a href="?a=detalhe_pedido&id=<?= \core\classes\Store::aesEcriptar($pedido->id_pedido) ?>"><?= $pedido->status ?></a>
                                        </td>
                                        <td><?= $pedido->updated_at ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    <?php endif; ?>

                </div>
            </div>
            
        </div>
    </div>
</main>

<script>
    function definir_filtro() {
        var filtro = document.getElementById("combo-status").value;
        // reload da página com determinado filtro
        window.location.href = window.location.pathname + "?" + $.param({
            'a': 'pedidos_instantaneos',
            'f': filtro
        });
    }
</script>