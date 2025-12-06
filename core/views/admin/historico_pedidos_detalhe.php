<style>
  .status-clicavel {
    cursor: pointer;
  }
</style>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h3>Detalhe do Pedido </h3>
    <div class="btn-toolbar mb-2 mb-md-0">
      <a href="?a=lista_pedidos" class="btn btn-sm btn-outline-secondary me-2">Voltar</a>
      <div class="btn-group me-2">

        <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>

      </div>


    </div>
  </div>

  <div class="container">

    <div class="row">
      <div class="col-md-12 me-0 p-0">

        <div class="row">

          <div class="col-3">
            <span><strong>Código da Pedido: </strong></span>
            <?= $pedido->codigo_pedido ?>
          </div>

          <div class="col-3 text-end">
            Comprovativo:
            <?php if ($pedido->comprovativo == null): ?>
              <small class="text-muted"><br>comprovativo não submetido</small>
            <?php else: ?>
              <small><br><a target="_blank" href="?a=ver_anexo_pedido&n=<?= $pedido->comprovativo ?>" class="text-primary" style="text-decoration: underline;"><strong><?= $pedido->comprovativo ?></strong></a></small>

            <?php endif; ?>

          </div>

          <div class="col text-end">
            <div class="text-center p-3 badge bg-primary status-clicavel" onclick="apresentarModal()"><?= $pedido->status  ?></div>
            <?php if ($pedido->status == 'EM PROCESSAMENTO'): ?>
              <div>
                <a target="_blank" href="?a=criar_pdf_pedido&id=<?= core\classes\Store::aesEcriptar($pedido->id_pedido) ?>" class="mt-2 btn btn-sm btn-outline-primary align-items-center"><i class="fa fa-file me-1"></i>PDF</a>
                <a href="?a=enviar_pdf_pedido&id=<?= core\classes\Store::aesEcriptar($pedido->id_pedido) ?>" class="mt-2 btn btn-sm btn-outline-primary align-items-center">Enviar PDF</a>
              </div>
            <?php endif; ?>
          </div>


        </div>

        <hr>
        <div class="row">

          <div class="col">
            <div class="my-2">
              <span><strong>Cliente</strong></span><br>
              <?= $pedido->cliente ?>
            </div>


            <div class="my-2">
              <span><strong>Email</strong></span><br>
              <?= $pedido->email ?>

            </div>
            <div class="my-2">
              <span><strong>Telefone</strong></span><br>
              <?= !empty($pedido->telefone) ? $pedido->telefone : '&nbsp;' ?>
            </div>
          </div>
          <div class="col">
            <div class="my-2">
              <span><strong>Modalidade: </strong></span>
                  <?php if ($pedido->modalidade == 'em casa'): ?>
                    Encomenda

                  <?php else: ?>
                    Na Hora
                  <?php endif; ?>
            </div>
            <div class="my-2">
              <span><strong>Data da pedido</strong></span><br>
              <?= $pedido->data_pedido ?>
            </div>


            <div class="my-2">
              <span><strong>Morada</strong></span><br>
              <?= $pedido->morada ?>
            </div>

            <div class="my-2">
            <span><strong>Estado do Pagamento</strong></span><br>

              <?php if (date('Y-m-d H:i:s') > $pedido->data_limite): ?>
                <?php if ($pedido->comprovativo == null): ?>
                  <small class="text-danger">Data para o pagamento expirada</small>
                <?php else: ?>
                  <small class="text-success">Pagamento foi feito dentro do prazo</small>

                <?php endif; ?>
              <?php else: ?>
                <br><strong><?= $pedido->data_limite ?></strong>
              <?php endif; ?>
            </div>

          </div>


          <div class="col-md-4 align-text-center">
            <div class="text-center">
              Estado do pedido
            </div>
            <div>
              <h4 class="text-center"><?= $pedido->status ?></h4>
            </div>
          </div>
        </div>

      </div>

      <hr class="my-3">
      <!-- dados da pedido -->
      <div class="row mb-3">
        <div class="col">
          <table class="table">
            <thead>
              <tr>
                <th></th>
                <th>Prato / Item do Cardapio</th>
                <th class="text-center">Quantidade</th>
                <th class="text-end">Preço / Uni.</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lista_produtos as $produto): ?>
                <tr>
                  <td><img src="../assets/img/menu/<?= $produto->imagem ?>" alt="" class="img-fluid" width="50px"></td>
                  <td><?= $produto->designacao_item ?></td>
                  <td class="text-center"><?= $produto->quantidade ?></td>
                  <td class="text-end"><?= number_format($produto->preco, 2, ',', '.') . ' kz' ?></td>
                </tr>





                <!-- total -->

              <?php endforeach; ?>
              <tr>
                <td colspan="4" class="text-end">Total: <strong><?= number_format($total, 2, ',', '.') . ' kz' ?></strong></td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>



    </div>
  </div>

</main>


<!-- modal -->
<div class="modal fade" id="modalStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Alterar estado do Pedido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <div class="text-center">
          <?php foreach (STATUS as $estado): ?>

            <?php if ($pedido->status == $estado): ?>
              <p><?= $estado ?></p>
            <?php else: ?>
              <p><a href="?a=pedido_alterar_estado&p=<?= core\classes\Store::aesEcriptar($pedido->id_pedido) ?>&s=<?= $estado ?>"><?= $estado ?></a></p>
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