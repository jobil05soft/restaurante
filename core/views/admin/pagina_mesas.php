<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>Mesas do Restaurante</h3>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-secondary align-items-center mx-2" onclick="apresentar_modal()"><i class="fas fa-plus"></i> Nova</button>
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Partilhar</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>

            </div>


        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php if (isset($_SESSION['erro'])): ?>
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
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h6> <?= $filtro ? ucfirst($filtro) : 'Todos' ?></h6>
        <div class="btn-toolbar mb-2 mb-md-0">

            <div class="btn-group me-2">
                <a href="?a=gestao_mesas&f=disponivel" type="button" class="btn btn-sm btn-outline-secondary">Disponivéis</a>
                <a href="?a=gestao_mesas&f=reservado" type="button" class="btn btn-sm btn-outline-secondary">Reservaddas</a>
                <a href="?a=gestao_mesas" type="button" class="btn btn-sm btn-outline-secondary">Todas</a>
            </div>

        </div>
    </div>


    <div class="container">
        <div class="row">

            <div class="table-responsive small">
                <table class="table table-striped table-sm" id="mesa">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" scope="col">Número da Mesa</th>
                            <th scope="col">Capacidade</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Criada Em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lista_mesas)): ?>
                            <?php foreach ($lista_mesas as $mesa): ?>
                                <tr>
                                    <td class="text-center"><?= $mesa->numero_mesa ?></td>
                                    <td><?= $mesa->capacidade ?></td>
                                    <td style="text-transform: uppercase; text-decoration:underline;" class="text-primary"><?= $mesa->status ?></td>
                                    

                                    <td><?= $mesa->created_at ?>
                                    <td>

                                        <a href="#"
                                            class="btn-editar"
                                            data-id="<?= $mesa->id_mesa ?>"
                                            title="Editar mesa"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarMesa">
                                            <i class="icon text-success fas fa-pen-to-square me-2"></i></a>

                                        <a href="?a=delete_mesa&id=<?= \core\classes\Store::aesEcriptar($mesa->id_mesa) ?>" title="eliminar mesa"><i class="icon text-danger fa-regular fa-trash-can me-2"></i></a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>

        <hr class="mt-3">
        <div class="row">
            <h5> Historico de Reserva</h5>
            <div class="mb-3">
                <label for="inputPassword" class="text-end col-form-label">Número da Mesa:</label>
                <div class="col-sm-4">
                    <select id="combo-status" class="form-control" onchange="definir_numero()">
                        <option value=""></option>
                        <?php foreach ($lista_mesas as $mesa): ?>
                            <?php ?>
                            <option value="<?= $mesa->id_mesa ?>"> <?= $mesa->numero_mesa ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- O histori vai aparecer aqui -->

                <div class="table-responsive small">
                    <table class="table table-striped table-sm" id="tabela">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" scope="col">Cliente</th>
                                <th scope="col">Telefone</th>
                                <th scope="col">Data & Hora</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Nº Pessoas</th>
                                <th scope="col">Tipo Refeiçao</th>
                                <th scope="col">Criado em</th>
                            </tr>
                        </thead>
                        <tbody id="historico-mesa">


                        </tbody>

                    </table>
                </div>


            </div>

        </div>
        
    </div>

</main>


<div class="modal fade" id="adicionar_mesa" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Item do Cardápio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="?a=form_adicionar_mesa" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="edit-nome" class="form-label">Número da mesa</label>
                        <input type="number" class="form-control" id="numero" name="numero" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Capacidade</label>
                        <input type="number" id="capacidade" name="capacidade" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Estado da mesa</label>
                        <select name="estado" id="" class="form-control" required>
                            <option value="disponivel">Disponível</option>
                            <option value="reservado">Reservado</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarMesa" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Item do Cardápio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="?a=form_editar_mesa" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3">
                        <label for="edit-nome" class="form-label">Número da mesa</label>
                        <input type="number" class="form-control" id="edit-numero" name="numero" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Capacidade</label>
                        <input type="number" id="edit-capacidade" name="capacidade" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Estado da mesa</label>
                        <select name="estado" id="edit-estado" class="form-control" required>
                            <option value="disponivel">Disponível</option>
                            <option value="reservado">Reservado</option>
                        </select>
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
        var modalStatus = new bootstrap.Modal(document.getElementById('adicionar_mesa'));
        modalStatus.show();
    }
</script>

<script>
    function definir_numero() {
        var mesa = document.getElementById("combo-status").value;
        carregarItens(mesa);
    }

    function carregarItens(mesa) {
        const url = '?a=historico_reserva_mesa&n=' + mesa;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const containerItens = document.getElementById('historico-mesa');


                // Renderizar itens
                containerItens.innerHTML = '';
                data.itens.forEach(item => {
                    const itemHTML = `
                        <tr>
                            <td class="text-center">${item.nome_completo}</td>
                            <td>${item.telefone}</td>
                            <td>${item.data} - ${item.hora_inicio}</td>
                            <td>${item.status}</td>
                            <td>${item.n_pessoa}</td>
                            <td>${item.tipo_refeicao}</td>
                            <td>${item.created_at}</td>
                        </tr>
                       
                    `;
                    containerItens.insertAdjacentHTML('beforeend', itemHTML);
                });


            })
            .catch(err => console.error('Erro ao buscar itens:', err));
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-editar').forEach(function(botao) {
            botao.addEventListener('click', function() {
                const itemId = this.dataset.id;

                fetch(`?a=editar_buscar_mesa&id=${itemId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Preencher campos do modal
                        // console.log(data)
                        document.getElementById('edit-id').value = data.id_mesa;
                        document.getElementById('edit-numero').value = data.numero_mesa;
                        document.getElementById('edit-capacidade').value = data.capacidade;
                        document.getElementById('edit-estado').value = data.status;
                    })
                    .catch(error => {
                        console.error('Erro ao carregar dados do item:', error);
                    });


            });
        });
    });
</script>