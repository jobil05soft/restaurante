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
        Alterar Dados
      </li>
    </ol>
  </nav>


  <div class="row">

    <div class="mt-2">

      <h4>Editar dados do Perfil</h4>
      <hr>





      <div class="card">
        <div class="container">
          <div class="row mt-2">

            <div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">

              <form action="?a=editar_perfil_submit" method="post">

                <div class="form-group">
                  <label>Email:</label>
                  <input type="email" maxlength="50" name="text_email" class="form-control" required value="<?= $dados_pessoais->email ?>">
                </div>

                <div class="form-group mt-2">
                  <label>Nome completo:</label>
                  <input type="text" maxlength="50" name="text_nome_completo" class="form-control" required value="<?= $dados_pessoais->nome_completo ?>">
                </div>

                <div class="form-group mt-2">
                  <label>Nome de Usuário:</label>
                  <input type="text" maxlength="50" name="text_nome_usuario" class="form-control" required value="<?= $dados_pessoais->nome_usuario ?>">
                </div>

                <div class="form-group mt-2">
                  <label>Morada:</label>
                  <input type="text" maxlength="100" name="text_morada" class="form-control" required value="<?= $dados_pessoais->endereco ?>">
                </div>

                <div class="form-group mt-2">
                  <label>Telefone:</label>
                  <input type="text" maxlength="20" name="text_telefone" class="form-control" value="<?= $dados_pessoais->telefone ?>">
                </div>

                <div class="text-center my-4">
                  <a href="?a=perfil" class="btn btn-primary btn-100">Cancelar</a>
                  <input type="submit" value="Salvar" class="btn btn-primary btn-100">
                </div>

              </form>

              <?php if (isset($_SESSION['mensagem'])): ?>
                <div class="alert alert-danger text-center p-2">
                  <?= $_SESSION['mensagem'] ?>
                  <?php unset($_SESSION['mensagem']) ?>
                </div>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>







    </div>
  </div>
</div>

</main>