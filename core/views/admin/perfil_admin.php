<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dados do Perfil</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

            <div class="btn-group me-2">

                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="apresentar_modal()"><span><i class="fas fa-user-edit"></i> Actualizar Dados</span></button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="apresentar_modal_edit_senha()"><span><i class="fas fa-key"></i> Alterar Palavra Passe</span></button>

            </div>
        </div>
    </div>

    <div class="row">

        <?php if (isset($_SESSION['alert'])): ?>
            <div class="alert alert-primary text-center p-2">
                <?= $_SESSION['alert'] ?>
                <?php unset($_SESSION['alert']) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="container">
            <div class="row my-5">
                <div class="col">

                    <table class="table table-striped">

                        <?php foreach ($dados_admin as $key => $value): ?>
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


</main>

<div class="modal fade" id="EditarDados" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Alterar dados de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="?a=alter_dados" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label">Nome Completo</label>
                        <input type="text" name="nome_completo" class="form-control" value="<?= $dados->nome_completo ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Nome de utilizador</label>
                        <input type="text" name="nome_usuario" class="form-control" value="<?= $dados->nome_usuario ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $dados->email ?> "required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Telefone</label>
                        <input type="tel" maxlength="9" name="telefone" class="form-control" value="<?= $dados->telefone ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Endereço</label>
                        <input type="text" name="endereco" class="form-control" value="<?= $dados->endereco ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Nível de Acesso</label>
                        <select name="tipo_user" id="" class="form-control" required>
                            <?php foreach ($niveis as $nivel): ?>
                                <option value="<?= $nivel->id_tipouser ?>"><?= ucfirst($nivel->nivel_acesso) ?></option>
                            <?php endforeach; ?>
                            <?php ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="EditarPassword" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Alterar Palavra Passe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="?a=alter_pass" method="post">

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

                        <input type="submit" value="Salvar" class="btn btn-primary btn-100">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function apresentar_modal() {
        var modalStatus = new bootstrap.Modal(document.getElementById('EditarDados'));
        modalStatus.show();
    }

    function apresentar_modal_edit_senha() {
        var modalStatus = new bootstrap.Modal(document.getElementById('EditarPassword'));
        modalStatus.show();
    }
</script>