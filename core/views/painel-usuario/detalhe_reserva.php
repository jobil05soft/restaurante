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
      <li class="breadcrumb-item">
        <a class="link-body-emphasis fw-semibold text-decoration-none" href="?a=historico_Reserva">
          <span class="">Histórico de Reserva</span>
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        Detalhe da Reserva
      </li>
    </ol>
  </nav>


  <div class="row">

    <div class="mt-2">

      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="row">


              <h4 class="">Detalhe da Reserva</h4>


              <div class="col-4">
                <span>Estado da Reserva:<strong> <?= $reserva->status ?></strong></span>
              </div>
              <?php if ($reserva->status == 'CONFIRMADA'): ?>
                <div class="col">
                  <a target="_blank" href="?a=pdf_reserva&id=<?= core\classes\Store::aesEcriptar($reserva->id_reversa) ?>" class="mt-2 btn btn-sm btn-outline-primary align-items-center"><i class="fas fa-download px-2"></i>PDF</a>

                </div>
              <?php endif; ?>
              <div class="col-4">

                <a class="btn btn-danger" href="?a=cancelar_reserva_usuario&id=<?= \core\classes\Store::aesEcriptar($reserva->id_reversa) ?>">Cancelar</a>


              </div>


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
                <?php if ($reserva->data_limite_pagamento != '0000-00-00 00:00:00'): ?>
                  Data de expiração do pagamento:<br><strong><?= $reserva->data_limite_pagamento ?></strong>
                <?php endif; ?>
              </div>


            </div>
          </div>

          <hr>

          <div class="row mt-4">
            <h5 class="">Comprovativo de Pagamento</h5>

            <div class="col-12">
              <div class="row">
                <p>Valor a Pagar <strong><?= number_format($valor, 2, ',', '.') . ' kz' ?></strong></p>

                <div class="col mt-1">
                  <strong class="text-primary">Método de Pagamento</strong>
                  <p>IBAN: <Strong><?= IBAN ?></Strong></p>
                  <p>Numero de conta: <Strong><?= CONTA ?></Strong></p>
                  <p>Transferência Express: <Strong><?= EXPRESS ?></Strong></p>
                  <p><small class="text-muted m-0">O comprovativo tem que ser submetido dentro de um pediodo de 2 horas</small></p>
                </div>

                <?php if ($reserva->status != 'CONFIRMADA'): ?>
                  <div class="col mt-3">
                    <strong class="text-primary">Submeter Comprovativo de Pagamento </strong>
                    <form class="mt-2" action="?a=submeter_pagamento_reserva" method="post" enctype="multipart/form-data">
                      <input type="number" name="id_reserva" value="<?= $reserva->id_reversa ?>" hidden>
                      <input type="file" name="comprovativo"> <br>
                      <button type="submit" class="btn btn-primary mt-3">Submeter</button><br>

                      <?= !$reserva->comprovativo == NULL ? '<small class="text-success">Se já submeteu o comprovativo não submeta novamente!</small>' : '<small class="text-danger">Se já submeteu o comprovativo não submeta novamente!</small>' ?>
                    </form>
                  </div>
                <?php endif; ?>


              </div>

              <div class="row">

                <div class="col-6 offset-3">
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
            </div>
          </div>



        </div>
      </div>
    </div>



  </div>
</div>


</div>
</main>