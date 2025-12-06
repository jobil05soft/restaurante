<style>
    .icon {
        font-size: 1.1em;
    }
</style>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>Gestão do Cardapio</h3>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-primary align-items-center mx-2" onclick="apresentar_modal()"><i class="fas fa-plus"></i> Novo</button>
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="apresentar_modal_categoria()">Categorias</button>
                <!-- <button type="button" class="btn btn-sm btn-outline-secondary" onclick="apresentar_modal_newCat()">Nova Categoria</button> -->

            </div>


        </div>
    </div>


    <div class="container">
        <div class="row">
            <?php

            use core\classes\Store;

            if (isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger text-center">
                    <?= $_SESSION['erro'] ?>
                    <?php unset($_SESSION['erro']); ?>
                </div>
            <?php endif; ?>

        </div>
        <div class="row">
            <?php if (isset($_SESSION['alert'])): ?>
                <div class="alert alert-success text-center">
                    <?= $_SESSION['alert'] ?>
                    <?php unset($_SESSION['alert']); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>


    <div class="container">
        <div class="row p-0">
            <h6 class="p-0">Prato do Dia
                <svg class="bi" width="24" height="24">
                    <use xlink:href="#icon-cardapio" />
                </svg>
            </h6>

            <?php if (!$prato_do_dia): ?>
                <p class="p-0 text-muted">Não tem um prato especial para o dia de Hoje! Podes definir uma mais a baixo</p>
            <?php else: ?>
                <div class="col-md-6 card">
                    <div class="row text-center">

                        <div class="col-md-6 p-0">
                            <img src="../assets/img/menu/<?= $prato_do_dia->imagem ?>" alt="prato do dia" class="img-fluid p-0 mx-0">
                        </div>

                        <div class="col-md-6 pt-5">
                            <h4 class="mt-4"><?= ucfirst($prato_do_dia->nome_item) ?></h4>
                            <p><?= $prato_do_dia->descricao ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>



        </div>
    </div>


    <hr>



    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h6>Categoria: <?= ucfirst(preg_replace("/\_/", " ", $c)) ?> </h6>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">

                <a type="button" href="?a=cardapio&c=todos" class="btn btn-sm btn-outline-secondary">Todos</a>
                <?php foreach ($categorias as $categoria) : ?>
                    <a href="?a=cardapio&c=<?= $categoria ?>" class="btn btn-sm btn-outline-secondary">
                        <?= ucfirst(preg_replace("/\_/", " ", $categoria)) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">

            <div class="table-responsive small">
                <table class="table table-striped table-sm" id="tabela">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Item</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Preço (kz)</th>
                            <th scope="col">Status</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- ciclo de apresentação do cardapio -->
                        <?php foreach ($itens_cardapio as $produto) : ?>

                            <!-- Verifica se esse é prato do dia -->
                            <tr class="text-primary">
                                <?php if ($produto->prato_dia == '1'): ?>
                                    <td class="bg-info"><img src="../assets/img/menu/<?= $produto->imagem ?>" class="img-fluid" width="40px"></td>
                                    <td class="bg-info"><?= $produto->nome_item ?></td>
                                    <td class="bg-info"><?= $produto->descricao ?></td>
                                    <td class="bg-info"><?= $produto->categoria ?></td>
                                    <td class="bg-info"><?= preg_replace("/\./", ",", $produto->preco) ?></td>

                                    <td class="bg-info">
                                        <?php if ($produto->visivel == 0): ?>
                                            <i class="fa fa-times text-danger"></i>
                                        <?php else: ?>
                                            <i class="fa fa-circle-check text-success"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="bg-info">

                                        <a href="#"
                                            class="btn-editar"
                                            data-id="<?= $produto->id_item ?>"
                                            title="Editar item do cardápio"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar">
                                            <i class="icon text-success fas fa-pen-to-square me-2"></i></a>

                                        <!-- <a href="" title="editar item do cardapio"><i class="icon text-success fas fa-pen-to-square me-2"></i></a> -->

                                        <?= $produto->visivel != 0 ? '<a href="?a=editar_status_item&id_item=' . Store::aesEcriptar($produto->id_item) . '&s=off" title="estado do item invisible"><i class="icon far fa-eye-slash me-2"></i></a>' : '<a href="?a=editar_status_item&id_item=' . Store::aesEcriptar($produto->id_item) . '&s=on" title="estad do item visible"><i class="icon far fa-eye me-2"></i></a>' ?>



                                        <a href="?a=delete_item_cardapio&id_item=<?= Store::aesEcriptar($produto->id_item) ?>" title="eliminar item do cardapio"><i class="icon text-danger fa-regular fa-trash-can me-2"></i></a>
                                        <a href="?a=prato_dia&acao=remover&id_item=<?= Store::aesEcriptar($produto->id_item) ?>" title="remover como prato do dia"><i class="icon text-danger fa-regular fa-square-minus me-2"></i></a>


                                    </td>

                                <?php else: ?>

                                    <td><img src="../assets/img/menu/<?= $produto->imagem ?>" class="img-fluid" width="40px"></td>
                                    <td><?= $produto->nome_item ?></td>
                                    <td><?= $produto->descricao ?></td>
                                    <td><?= $produto->categoria ?></td>
                                    <td><?= preg_replace("/\./", ",", $produto->preco) ?></td>

                                    <td>
                                        <?php if ($produto->visivel == 0): ?>
                                            <i class="fa fa-times text-danger"></i>
                                        <?php else: ?>
                                            <i class="fa fa-circle-check text-success"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>

                                        <a href="#"
                                            class="btn-editar"
                                            data-id="<?= $produto->id_item ?>"
                                            title="Editar item do cardápio"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar">
                                            <i class="icon text-success fas fa-pen-to-square me-2"></i></a>

                                        <!-- <a href="" title="editar item do cardapio"><i class="icon text-success fas fa-pen-to-square me-2"></i></a> -->

                                        <?= $produto->visivel != 0 ? '<a href="?a=editar_status_item&id_item=' . Store::aesEcriptar($produto->id_item) . '&s=off" title="estado do item invisible"><i class="icon far fa-eye-slash me-2"></i></a>' : '<a href="?a=editar_status_item&id_item=' . Store::aesEcriptar($produto->id_item) . '&s=on" title="estad do item visible"><i class="icon far fa-eye me-2"></i></a>' ?>



                                        <a href="?a=delete_item_cardapio&id_item=<?= Store::aesEcriptar($produto->id_item) ?>" title="eliminar item do cardapio"><i class="icon text-danger fa-regular fa-trash-can me-2"></i></a>
                                        <a href="?a=prato_dia&acao=definir&id_item=<?= Store::aesEcriptar($produto->id_item) ?>" title="definir prato do dia"><i class="icon text-primary fa-regular fa-square-check me-2"></i></a>

                                    </td>
                                <?php endif; ?>
                            </tr>


                        <?php endforeach; ?>

                    </tbody>

                </table>
            </div>
        </div>

    </div>

</main>


<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Novo Prato ao Cardápio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="?a=adicionar_novo_item" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label">Nome do Prato</label>
                        <input type="text" name="prato" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Detalhes</label>
                        <input type="text" name="detalhes" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Categoria</label>
                        <input type="text" name="categoria" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Preço</label>
                        <input type="number" name="preco" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Imagem do prato</label>
                        <input type="file" name="ficheiro" class="form-control">
                    </div>
                    <!-- <div class="mb-3">
                        <label for="mealType" class="form-label">Tipo de Refeição</label>
                        <select name="mealType" class="form-select" required>
                            <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $caategoria ?>"><?= ucfirst(preg_replace("/\_/", " ", $categoria)) ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Item do Cardápio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="?a=form_editar_item" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3">
                        <label for="edit-nome" class="form-label">Nome do Prato</label>
                        <input type="text" class="form-control" id="edit-nome" name="prato">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Detalhes</label>
                        <input type="text" id="edit-detalhe" name="detalhes" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Categoria</label>
                        <input type="text" id="edit-categoria" name="categoria" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="edit-preco" class="form-label">Preço</label>
                        <input type="text" class="form-control" id="edit-preco" name="preco">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Imagem do prato</label>
                        <input type="file" id="" name="ficheiro" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--  Modal de categorias -->
<div class="modal fade" id="modalCategorias" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Lista de Categorias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <?php foreach ($categorias as $categoria): ?>
                    <li>
                        <?= $categoria ?>

                    </li>
                <?php endforeach; ?>
            </div>

            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-sm btn-outline-secondary" onclick="apresentar_modal_newCat()" >Nova Categoria</button> -->
            </div>
        </div>
    </div>
</div>

<!--  Modal de categorias - nova categoria 
<div class="modal fade" id="newCat" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Nova categoria de cardapio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="edit-nome" class="form-label">Nome do Prato</label>
                        <input type="text" class="form-control" id="edit-nome" name="prato">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Detalhes</label>
                        <input type="text" id="edit-detalhe" name="detalhes" class="form-control">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
-->
<script>
    function apresentar_modal() {
        var modalStatus = new bootstrap.Modal(document.getElementById('reservationModal'));
        modalStatus.show();
    }
</script>

<script>
    function apresentar_modal_categoria() {
        var modalStatus = new bootstrap.Modal(document.getElementById('modalCategorias'));
        modalStatus.show();
    }

    function apresentar_modal_newCat() {
        var modalStatus = new bootstrap.Modal(document.getElementById('newCat'));
        modalStatus.show();
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-editar').forEach(function(botao) {
            botao.addEventListener('click', function() {
                const itemId = this.dataset.id;

                fetch(`?a=editar&id=${itemId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Preencher campos do modal
                        document.getElementById('edit-id').value = data.id_item;
                        document.getElementById('edit-nome').value = data.nome_item;
                        document.getElementById('edit-detalhe').value = data.descricao;
                        document.getElementById('edit-categoria').value = data.categoria;
                        document.getElementById('edit-preco').value = data.preco;
                    })
                    .catch(error => {
                        console.error('Erro ao carregar dados do item:', error);
                    });
            });
        });
    });
</script>