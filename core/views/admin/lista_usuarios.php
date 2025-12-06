<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>Lista usuarios</h3>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="apresentar_modal()"><span><i class="fas fa-user-plus"></i> | Novo Usuário</span></button>

            </div>
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>

            </div>


        </div>
    </div>


    <div class="col-md-12">


        <div class="container">

            <div class="row">
                <?php if (isset($_SESSION['alert'])): ?>
                    <div class="alert alert-success text-center">
                        <?= $_SESSION['alert'] ?>
                        <?php unset($_SESSION['alert']); ?>
                    </div>
                <?php endif; ?>

            </div>

            <div class="row">

                <div class="table-responsive small">
                    <?php if (count($usuarios) == 0) : ?>
                        <hr>
                        <p class="text-center text-muted">Não existem usuarios registados.</p>
                        <hr>
                    <?php else : ?>

                        <table class="table table-striped" id="tabela">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nome Completo</th>
                                    <th>Nome de usuario</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th class="text-center">Nivel de Acesso</th>
                                    <th class="text-center">Criado em</th>
                                    <th class="text-center">Ativo</th>
                                    <th class="text-center">Eliminado</th>
                                    <th>Açao</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>

                                        <td><?= $usuario->nome_completo ?></td>
                                        <td>
                                            <!-- <a href="?a=detalhe_usuario&c=<?= \core\classes\Store::aesEcriptar($usuario->id_pessoa) ?>"><?= $usuario->nome_completo ?></a> -->
                                            <?= $usuario->nome_usuario ?>
                                        </td>

                                        <td> <?= $usuario->email ?></td>
                                        <td><?= $usuario->telefone ?></td>

                                        <td class="text-center">
                                            <?= ucfirst($usuario->nivel_acesso) ?>
                                        </td>

                                        <td class="text-center">
                                            <?= date('d/m/y H:i:s', strtotime($usuario->created_at)) ?>
                                        </td>


                                        <!-- ativo -->
                                        <td class="text-center">
                                            <?php if ($usuario->ativo == 1): ?>
                                                <i class="text-success fas fa-check-circle"></i></span>
                                            <?php else: ?>
                                                <i class="text-danger fas fa-times-circle"></i></span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- eliminado -->
                                        <td class="text-center">
                                            <?php if ($usuario->deleted_at == null): ?>
                                                <i class="text-danger fas fa-times-circle"></i></span>
                                            <?php else: ?>
                                                <i class="text-success fas fa-check-circle"></i></span>
                                            <?php endif; ?>
                                        </td>


                                        <td>

                                            <?php if ($usuario->deleted_at == null): ?>
                                                <!-- alter nivel de acesso -->
                                                <!-- <a href="#"
                                                    class="btn-editar"
                                                    data-id="<?= $usuario->id_usuario ?>"
                                                    title="Editar mesa"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalAterar">

                                                    <i class="icon text-success fas fa-pen-to-square me-2"></i></a> -->


                                                <?php if ($usuario->ativo == 1): ?>
                                                    <!-- desativar -->
                                                    <a class="mx-1" href="?a=op_usuario&id=<?= \core\classes\Store::aesEcriptar($usuario->id_usuario) ?>&op=desativar"><i class="fas fa-user-slash text-danger"></i></a>
                                                <?php else: ?>
                                                    <!-- ativar -->
                                                    <a class="mx-1" href="?a=op_usuario&id=<?= \core\classes\Store::aesEcriptar($usuario->id_usuario) ?>&op=ativar"><i class="fas fa-user-check"></i></a>
                                                <?php endif; ?>



                                                <a class="mx-1" href="?a=op_usuario&id=<?= \core\classes\Store::aesEcriptar($usuario->id_usuario) ?>&op=eliminar"><i class="fas fa-trash text-danger"></i></a>

                                            <?php endif; ?>


                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    <?php endif; ?>

                </div>
            </div>
            
        </div>
    </div>
</main>


<div class="modal fade" id="novoUsuario" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Novo Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="?a=novouser_submit" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label">Nome Completo</label>
                        <input type="text" name="nome_completo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Nome de utilizador</label>
                        <input type="text" name="nome_usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Telefone</label>
                        <input type="tel" maxlength="9" name="telefone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Endereço</label>
                        <input type="text" name="endereco" class="form-control" required>
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

<div class="modal fade" id="modalAterar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Alterar Nivel de Acesso do Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="?a=form_editar_item" method="post">

                    <input type="hidden" id="edit-id" name="id">


                    <div class="mb-3" id="edit-nivel">


                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    function apresentar_modal() {
        var modalStatus = new bootstrap.Modal(document.getElementById('novoUsuario'));
        modalStatus.show();
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-editar').forEach(function(botao) {
            botao.addEventListener('click', function() {
                const itemId = this.dataset.id;

                fetch(`?a=buscar_niveis`)


                    .then(response => response.json())
                    .then(data => {
                        const containerItens = document.getElementById('edit-nivel');


                        // Renderizar itens
                        containerItens.innerHTML = '';
                        data.itens.forEach(item => {
                            const itemHTML = `
                        
                                <label for="" class="form-label">Nível de Acesso</label>
                                <select name="tipo_user" class="form-control" required>
                                    <option value"${item.id_tipouser}">${item.nivel_acesso}</option>
                                </select>
                       
                            `;
                            containerItens.insertAdjacentHTML('beforeend', itemHTML);
                        });

                    })
                    .catch(error => {
                        console.error('Erro ao carregar dados do item:', error);
                    });

            });

            console.log(data);
        });
    });
</script>