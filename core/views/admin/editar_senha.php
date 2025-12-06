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
        <a class="link-body-emphasis fw-semibold text-decoration-none" href="?a=perfil">Usuario</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        Editar Senha
      </li>
    </ol>
  </nav>


  <div class="row">

    <div class="mt-2">

      <h4>Editar Senha</h4>
      <hr>




      <div class="container">
        <div class="row my-5">
          <div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">

            <form action="?a=alterar_senha_submit" method="post">

              <div class="form-group">
                <label>Senha atual:</label>
                <input type="password" maxlength="30" name="text_senha_antiga" class="form-control" required>
              </div>

              <div class="form-group">
                <label>Nova senha:</label>
                <input type="password" maxlength="30" name="text_senha_nova_1" class="form-control" required>
              </div>

              <div class="form-group">
                <label>Repetir nova senha:</label>
                <input type="password" maxlength="30" name="text_senha_nova_2" class="form-control" required>
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

</main>