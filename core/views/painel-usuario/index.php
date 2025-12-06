<style>
  .text-a1a1a1 {
    color: #a1a1a1;
  }
</style>

<div class="container-fluid my-3 px-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
      <li class="breadcrumb-item">
        <a class="link-body-emphasis" href="#">
          <svg class="bi pe-none me-2" width="16" height="16">
            <use xlink:href="#home" />
          </svg>
          <span class="visually-hidden">Inicio</span>
        </a>
      </li>

      <li class="breadcrumb-item active" aria-current="page">
        Página Inicial
      </li>
    </ol>
  </nav>


  <div class="row">
    <div class="col-12">

      <h3 class="mb-3">Histórico de Reservas</h3>
      <hr>

      <div class="col-md-12">

        <!-- apresenta informações sobre o total de pedidos e reservas PENDENTES -->
        <h6>Reservas Pendentes</h6>
        <?php if ($total_reservas_pendentes == 0): ?>
          <p class="text-a1a1a1">Não existem reservas pendentes.</p>
        <?php else: ?>
          <div class="alert alert-info p-2">
            <span class="me-3">Existem reservas pendentes: <strong><?= $total_reservas_pendentes ?></strong></span>
            <a href="?a=historico_reservas">Ver</a>
          </div>
        <?php endif; ?>

        <hr>
        <!-- apresenta informações sobre o total de pedido EM PROCESSAMENTO -->
        <h6>Pedidos Pendentes</h6>
        <?php if ($total_pedido_processamento == 0): ?>
          <p class="text-a1a1a1">Não existem pedido em processamento.</p>
        <?php else: ?>
          <div class="alert alert-warning p-2">
            <span class="me-3">Existem pedido em processamento: <strong><?= $total_pedido_processamento ?></strong></span>
            <a href="?a=historico_pedidos">Ver</a>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

</div>
</main>