<div class="container my-3">
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
        <a class="link-body-emphasis fw-semibold text-decoration-none" href="?a=perfil">Usuário</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        Perfil
      </li>
    </ol>
  </nav>


  <div class="row">

    <div class="mt-2">

      <h4>Perfil</h4>
      <hr>

      <div class="card">
        <div class="container">
          <div class="row my-5">
            <div class="col">

              <table class="table table-striped">

                <?php foreach ($dados_cliente as $key => $value): ?>
                  <tr>
                    <td class="text-end" width="40%"><?= $key ?>:</td>
                    <td width="60%"><strong><?= $value ?></strong></td>
                  </tr>
                <?php endforeach; ?>

              </table>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

</main>