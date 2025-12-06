<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h3>Painel Inicial</h3>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group me-2">
        <button type="button" class="btn btn-sm btn-outline-secondary">Partilhar</button>
        <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>
      </div>
      <button type="button"
        class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1">
        <svg class="bi">
          <use xlink:href="#calendar3" />
        </svg>
        Esta Semana
      </button>
    </div>
  </div>


  <div class="container">
    <div class="row p-0">
      <div class="col-md-6 me-3 card">
        <div class="row border-bottom pt-3">
          <h6>
            <svg class="bi" width="24" height="24">
              <use xlink:href="#calendar-check" />
            </svg>
            Reservas
          </h6>
        </div>
        <div class="row text-center">
          <div class="col-md-6 p-5" style="border-right: 1px solid #c3c3c3;">
            <h2 class="text-primary"><a href="?a=lista_reservas" id="total-reserva" class="ms-2"></a></h2>
            <p>Total de Reservas</p>
          </div>
          <div class="col-md-6 p-5">
            <h2 class="text-primary"><?= count($lista_reservas) ?></h2>
            <p>Reservas marcadas para Hoje</p>
          </div>
        </div>
      </div>

      <div class="col-md-5 card">
        <div class="row border-bottom pt-3">
          <h6>
            <svg class="bi" width="24" height="24">
              <use xlink:href="#icon-pedido" />
            </svg> Pedidos
          </h6>
        </div>
        <div class="row text-center">
          <div class="col-md-6 p-5" style="border-right: 1px solid #c3c3c3;">
            <h2 class="text-primary"><a href="?a=pedidos_instantaneos" id="total_pedido_local"></a></h2>
            <p>Pedidos no Local</p>
          </div>
          <!--  -->
          <div class="col-md-6 p-5">
            <h2 class="text-primary"><a href="?a=lista_pedidos" id="total_pedido_encomenda"></a></h2>
            <p>Pedidos por encomenda</p>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="container">
    <div class="row">
      <h3 class="mt-5 mb-4">Reservas de Hoje</h3>
      <div class="table-responsive small">
        <table class="table table-striped table-sm" id="tabela">
          <thead class="table-dark">
            <tr>
              <th class="text-center" scope="col">Cliente</th>
              <th scope="col">Data</th>
              <th scope="col">Periodo</th>
              <th scope="col">Pessoas</th>
              <th scope="col">Mesa</th>
              <th scope="col">Status</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($lista_reservas as $reserva): ?>
              <tr>
                <td class="text-center"><?= $reserva->cliente ?></td>
                <td><?= $reserva->data ?> - <?= $reserva->hora_inicio ? date('H:i', strtotime($reserva->hora_inicio)) : '-----' ?> </td>
                <td><?= $reserva->tipo_refeicao ?></td>
                <td><?= $reserva->n_pessoa ? $reserva->n_pessoa : '-' ?></td>
                <td><?= $reserva->mesa ? $reserva->mesa : '-' ?></td>

                <td class="text-primary" style="cursor: pointer;">
                  <a href="?a=detalhe_reserva&id=<?= \core\classes\Store::aesEcriptar($reserva->id_reversa) ?>"> <?= $reserva->status ?></a>

                </td>

                <td>

                  <?php if ($_SESSION['admin']): ?>
                    <?= $reserva->updated_at ?>
                  <?php else: ?>
                    <a href=""><i class="mx-1 far fa-circle-check text-success"></i></a>
                    <a href=""><i class="mx-1 fas fa-cancel text-danger"></i></a>
                    <a href=""><i class="mx-1 fas fa-edit text-info"></i></a>
                  <?php endif; ?>


                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>



<script>
  document.addEventListener('DOMContentLoaded', function() {

    total_pedido();
    total_pedido_encomenda();
    total_reserva();

    function total_reserva() {

      axios.defaults.withCredentials = true; // permissão do AXIOS
      axios.get('?a=carregar_total_reserva')
        .then(function(response) {

          var total_item = response.data;
          document.getElementById('total-reserva').innerHTML = total_item;
        });
    }

    function total_pedido() {

      axios.defaults.withCredentials = true; // permissão do AXIOS
      axios.get('?a=carregar_total_pedido')
        .then(function(response) {

          var dados = response.data;
          document.getElementById('total_pedido_local').innerHTML = dados;
        });
    }

    function total_pedido_encomenda() {

      axios.defaults.withCredentials = true; // permissão do AXIOS
      axios.get('?a=total_pedido_encomenda')
        .then(function(response) {

          var dados = response.data;
          document.getElementById('total_pedido_encomenda').innerHTML = dados;
        });
    }
  });


  setInterval(total_pedido, 4000);
  setInterval(total_pedido_encomenda, 4000);
  setInterval(total_reserva, 4000);
</script>