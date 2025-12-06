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
        <a class="link-body-emphasis fw-semibold text-decoration-none" href="?a=historico_pedidos">
          <span class="">Histórico de Pedidos</span>
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        Detalhe do Pedido
      </li>
    </ol>
  </nav>


  <div class="row">

    <div class="mt-2">

      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="row">


              <h4 class="col-6">Detalhe do Pedido</h4>


              <div class="col-6">
                <span><strong>Código da pedido: </strong></span>
                <?= $dados_pedido->codigo_pedido ?>
              </div>

            </div>
            <hr>
            <div class="row">
              <div class="col">
                <div class="col-6">
                  <span><strong>Modalidade: </strong></span>
                  <?php if ($dados_pedido->modalidade == 'em casa'): ?>
                    Encomenda

                  <?php else: ?>
                    Na Hora
                  <?php endif; ?>
                </div>
                <div class="p-2 my-3">
                  <span><strong>Data da pedido</strong></span><br>
                  <?= $dados_pedido->data_pedido ?>
                </div>
                <div class="p-2 my-3">
                  <span><strong>Morada</strong></span><br>
                  <?= $dados_pedido->morada ?>
                </div>
              </div>
              <div class="col">
                <div class="p-2 my-3">
                  <span><strong>Email</strong></span><br>
                  <?= $dados_pedido->email ?>
                </div>
                <div class="p-2 my-3">
                  <span><strong>Telefone</strong></span><br>
                  <?= !empty($dados_pedido->telefone) ? $dados_pedido->telefone : '&nbsp;' ?>
                </div>
              </div>
              <div class="col align-self-center">
                <div class="text-center mb-3">
                  Estado do pedido
                </div>
                <div>
                  <h5 class="text-center"><?= $dados_pedido->status ?></h5>
                </div>
              </div>
            </div>

            <!-- dados da pedido -->
            <div class="row mb-3">
              <div class="col">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Prato / Item do Cardapio</th>
                      <th class="text-center">Quantidade</th>
                      <th class="text-end">Preço / Uni.</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($produtos_pedido as $produto): ?>
                      <tr>
                        <td><?= $produto->nome_item ?></td>
                        <td class="text-center"><?= $produto->quantidade ?></td>
                        <td class="text-end"><?= number_format($produto->preco, 2, ',', '.') . ' kz' ?></td>
                      </tr>
                    <?php endforeach; ?>
                    <tr>
                      <td colspan="3" class="text-end">Total: <strong><?= number_format($total_pedido, 2, ',', '.') . ' kz' ?></strong></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="row">
              <h5 class="">Comprovativo de Pagamento</h5>

              <div class="col-12">
                <div class="row">
                  <div class="col mt-1">
                    <strong class="text-primary">Método de Pagamento</strong>
                    <p>IBAN: <Strong><?= IBAN ?></Strong></p>
                    <p>Numero de conta: <Strong><?= CONTA ?></Strong></p>
                    <p>Transferência Express: <Strong><?= EXPRESS ?></Strong></p>
                    <p><small class="text-muted m-0">O comprovativo tem que ser submetido dentro de um pediodo de 2 horas</small></p>
                  </div>

                  <?php if ($dados_pedido->status != 'CONCLUIDA'): ?>
                    <div class="col mt-3">
                      <strong class="text-primary">Submeter Comprovativo de Pagamento </strong>
                      <form class="mt-2" action="?a=submeter_pagamento_pedido" method="post" enctype="multipart/form-data">
                        <input type="number" name="id_pedido" value="<?= $dados_pedido->id_pedido ?>" hidden>
                        <input type="file" name="comprovativo"> <br>
                        <button type="submit" class="btn btn-primary mt-3">Submeter</button><br>

                        <?= !$dados_pedido->comprovativo == NULL ? '<small class="text-success">Se já submeteu o comprovativo não submeta novamente!</small>' : '<small class="text-danger">Se já submeteu o comprovativo não submeta novamente!</small>' ?>
                      </form>
                    </div>
                  <?php endif; ?>
                </div>
              </div>




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
</main>