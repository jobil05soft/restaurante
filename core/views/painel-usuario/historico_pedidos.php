<div class="container-fluid my-3 px-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
      <li class="breadcrumb-item">
        <a class="link-body-emphasis" href="#">
          <svg class="bi pe-none me-2" width="16" height="16">
            <use xlink:href="#home" />
          </svg>
          <span class="visually-hidden">Home</span>
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        Histórico de Pedidos
      </li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-12">
      <h3 class="mb-3">Histórico de Pedidos</h3>
      <hr>

      <?php if (count($historico_pedidos) == 0) : ?>
        <p class="text-muted">Não existem pedidos registados.</p>
        <hr>
      <?php else : ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle table-bordered small">
            <thead class="table-dark">
              <tr>
                <th>Data do Pedido</th>
                <th>Código</th>
                <th>Modalidade</th>
                <th>Estado</th>
                <th>Ação</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($historico_pedidos as $pedido) : ?>
                <tr>
                  <td><?= $pedido->data_pedido ?></td>
                  <td><?= $pedido->codigo_pedido ?></td>
                  <td><?= ucfirst($pedido->modalidade) ?></td>
                  <td><?= $pedido->status ?></td>
                  <td>
                    <a href="?a=detalhe_pedido&id=<?= core\classes\Store::aesEcriptar($pedido->id_pedido) ?>" class="btn btn-sm btn-primary">Detalhes</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <p class="text-end">Total de pedidos: <strong><?= count($historico_pedidos) ?></strong></p>
      <?php endif; ?>
    </div>
  </div>
</div>


</div>
</main>