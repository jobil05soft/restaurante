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
        Histórico de Reservas
      </li>
    </ol>
  </nav>


  <div class="row">
    <div class="col-12">
      <h3 class="mb-3">Histórico de Reservas</h3>
      <hr>

      <?php if (count($historico_reservas) == 0) : ?>
        <p class="">Não existem reservas registadas.</p>
        <hr>
      <?php else : ?>

        <div class="table-responsive">
          <table class="table table-striped align-middle table-bordered small">
            <thead class="table-dark">
              <tr>
                <th>Data da reserva</th>
                <th>Período</th>
                <th class="text-center">Nº da Mesa</th>
                <th class="text-center">Nº de Pessoa</th>
                <th>Estado</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($historico_reservas as $reserva) : ?>
                <tr>
                  <td><?= $reserva->data ?> || <?= date('H:i', strtotime($reserva->hora_inicio)) ?></td>
                  <td><?= $reserva->tipo_refeicao ?></td>
                  <td class="text-center"><?= $reserva->numero_mesa ?></td>
                  <td class="text-center"><?= $reserva->n_pessoa ?></td>

                  <?php if ($reserva->estado == 'CONFIRMADA'): ?>
                    <td style="cursor: pointer;">

                      <a class="text-success" href="?a=detalhe_reserva&id=<?= \core\classes\Store::aesEcriptar($reserva->id_reversa) ?>"> <?= $reserva->estado ?></a>
                    </td>

                  <?php elseif ($reserva->estado == 'CANCELADA'): ?>
                    <td style="cursor: pointer;">

                      <a class="text-danger" href="?a=detalhe_reserva&id=<?= \core\classes\Store::aesEcriptar($reserva->id_reversa) ?>"> <?= $reserva->estado ?></a>
                    </td>

                  <?php else: ?>
                    <!-- <td style="text-transform: uppercase;"><?= $reserva->estado ?></td> -->
                    <td class="text-primary" style="cursor: pointer;">
                      <a href="?a=detalhe_reserva&id=<?= \core\classes\Store::aesEcriptar($reserva->id_reversa) ?>"> <?= $reserva->estado ?></a>

                    </td>

                  <?php endif; ?>



                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>


        <p class="text-end">Total reservas: <strong><?= count($historico_reservas) ?></strong></p>

      <?php endif; ?>
    </div>
  </div>
</div>



</div>
</main>