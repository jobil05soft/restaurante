<style>
  .status-clicavel {
    cursor: pointer;
  }
</style>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detalhe da Reserva</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
      <a href="?a=lista_reservas" class="btn btn-sm btn-outline-primary align-items-center mx-2"><i class="fas fa-back"></i> Voltar</a>
    </div>
  </div>




  <div class="container">

    <div class="row">

      <div class="col-md-12 me-0 p-0">
        <div class="row">

          <div class="col-3">
            Valor a Pagar <br><strong><?= number_format($valor, 2, ',', '.') . ' kz' ?></strong>
          </div>

          <div class="col-3 text-end">
            Comprovativo:
            <?php if ($reserva->comprovativo == null): ?>
              <small class="text-muted"><br>comprovativo não submetido</small>
            <?php else: ?>
              <small><br><a target="_blank" href="?a=ver_anexo&n=<?= $reserva->comprovativo ?>" class="text-primary" style="text-decoration: underline;"><strong><?= $reserva->comprovativo ?></strong></a></small>

            <?php endif; ?>

          </div>

          <div class="col-3 text-end mt-1">
            Presença: <br>
            <?php if ($reserva->presenca == 'sim'): ?>
              <span class='text-success'><?= ucfirst($reserva->presenca) ?></span>
            <?php else: ?>
              <span class='text-danger'><?= ucfirst($reserva->presenca) ?></span>

            <?php endif; ?>
          </div>



          <?php if ($_SESSION['tipo_admin'] == 'admin'): ?>
            <div class="col-3 text-end">


              <div class="text-center p-3 badge bg-primary status-clicavel" onclick="apresentarModal()"><?= $reserva->status ?></div>
              <?php if ($reserva->status == 'CONFIRMADA'): ?>
                <div>
                  <a target="_blank" href="?a=criar_pdf_reserva&r=<?= core\classes\Store::aesEcriptar($reserva->id_reversa) ?>" class="mt-2 btn btn-sm btn-outline-primary align-items-center"><i class="fa fa-file me-1"></i>PDF</a>
                  <a href="?a=enviar_pdf_reserva&r=<?= core\classes\Store::aesEcriptar($reserva->id_reversa) ?>" class="mt-2 btn btn-sm btn-outline-primary align-items-center">Enviar PDF</a>
                </div>
              <?php endif; ?>

            </div>
          <?php endif; ?>

        </div>

        <hr>


        <div class="row">
          <div class="col">
            Cliente: <br><strong><?= $reserva->cliente ?></strong>
          </div>
          <div class="col">
            Email:<br><strong><?= $reserva->email ?></strong>
          </div>

          <div class="col">
            Telefone:<br><strong><?= $reserva->telefone ?></strong>

          </div>

          <div class="col">
            Morada:<br><strong><?= $reserva->endereco ?></strong>
          </div>


        </div>

        <hr>

        <div class="row mt-3">



          <div class="col">
            Horário:<br><strong><?= date('H:i', strtotime($reserva->hora_inicio)) ?> - <?= date('H:i', strtotime($reserva->hora_fim)) ?> </strong>
          </div>

          <div class="col">
            Tipo de refeição: <br><strong><?= $reserva->tipo_refeicao ?></strong>
          </div>

          <div class="col">
            Numero da Mesa:<br><strong><?= $reserva->mesa ?></strong>
          </div>

          <div class="col">
            Numero de Pessoa:<br><strong><?= $reserva->n_pessoa ?></strong>
          </div>
          <br>


        </div>
        <hr>
        <div class="row mt-4">

          <div class="col">
            Data reserva:<br><strong><?= $reserva->data ?></strong>
          </div>
          <div class="col">
            Feita em :<br><strong><?= date('Y-m-d H:i', strtotime($reserva->created_at)) ?></strong>
          </div>


          <div class="col">
            Atulização: <br><strong><?= date('Y-m-d H:i', strtotime($reserva->updated_at))  ?></strong>
          </div>

          <div class="col">
            Estado do Pagamento <br>
            <?php if (date('Y-m-d H:i:s') > $reserva->data_limite_pagamento): ?>
              <?php if ($reserva->comprovativo == null): ?>
                <small class="text-danger">Data para o pagamento expirada</small>

              <?php else: ?>
                <small class="text-success">Pagamento foi feito dentro do prazo</small>


              <?php endif; ?>
            <?php else: ?>
              <strong><?= $reserva->data_limite_pagamento ?></strong>
            <?php endif; ?>
          </div>

        </div>


      </div>

      <hr>
      <div class="col my-0 p-0">
        <strong>Mensagem</strong>
        <p>
          <?= $reserva->mensagem ?>.
        </p>
      </div>
      <hr>

      <div class="row mt-4">
        <div class="col-3 offset-3 text-end mt-1">

          <a href="?a=reserva_alterar_presenca&r=<?= core\classes\Store::aesEcriptar($reserva->id_reversa) ?>&s=PRESENTE" class="btn btn-sm btn-outline-info align-items-center" title="Marcar Presença do Cliente"><i class="fas fa-user-check px-2"></i></a>
          <a href="?a=reserva_alterar_presenca&r=<?= core\classes\Store::aesEcriptar($reserva->id_reversa) ?>&s=AUSENTE" class="btn btn-sm btn-outline-danger align-items-center" title="Marcar Ausencia do Cliente"><i class="fas fa-user-slash px-2"></i></a>
        </div>
      </div>



    </div>


    <div class="mt-3">
      <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-success text-center p-2">
          <?= $_SESSION['alert'] ?>
          <?php unset($_SESSION['alert']) ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger text-center p-2">
          <?= $_SESSION['erro'] ?>
          <?php unset($_SESSION['erro']) ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

</main>

<!-- modal -->
<div class="modal fade" id="modalStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Alterar estado da reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <div class="text-center">
          <?php foreach (STATUS_RESERVA as $estado): ?>

            <?php if ($reserva->status == $estado): ?>
              <p><?= $estado ?></p>
            <?php else: ?>
              <p><a href="?a=reserva_alterar_estado&r=<?= core\classes\Store::aesEcriptar($reserva->id_reversa) ?>&s=<?= $estado ?>"><?= $estado ?></a></p>
            <?php endif; ?>



          <?php endforeach; ?>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script>
  function apresentarModal() {
    var modalStatus = new bootstrap.Modal(document.getElementById('modalStatus'));
    modalStatus.show();
  }
</script>