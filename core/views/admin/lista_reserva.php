<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 >Lista de Reservas</h3>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-primary align-items-center mx-2"><i class="fas fa-plus"></i> Novo</button>
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Partilhar</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>

            </div>


        </div>
    </div>

    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h6> <?= $data ? 'Reserva de <strong> ' . $data : 'Todas Reservas' ?></strong> no Periodo: <strong><?= $filtro ? $filtro : 'Todas Refeições' ?></strong></h6>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">

                <a type="button" href="?a=lista_reservas&data=today<?= $filtro_get ? '&f=' . $filtro_get : '' ?>" class="btn btn-sm btn-outline-secondary">Hoje</a>
                <a type="button" href="?a=lista_reservas&data=tomorrow&f=<?= $filtro_get ?>" class="btn btn-sm btn-outline-secondary">Amanhã</a>
                <a type="button" href="?a=lista_reservas&data=last7days&f=<?= $filtro_get ?>" class="btn btn-sm btn-outline-secondary">Últimos 7 dias</a>
                <a type="button" href="?a=lista_reservas&data=next7days&f=<?= $filtro_get ?>" class="btn btn-sm btn-outline-secondary">Próximos 7 dias</a>
                <a type="button" href="?a=lista_reservas&data=thisMonth&f=<?= $filtro_get ?>" class="btn btn-sm btn-outline-secondary">Este Mês</a>
            </div>
            <div class="btn-group me-2">
                <a href="?a=lista_reservas&data=<?= $data_get ?>&f=cafe_manha" type="button" class="btn btn-sm btn-outline-secondary">Café da Manhã</a>
                <a href="?a=lista_reservas&data=<?= $data_get ?>&f=almoco" type="button" class="btn btn-sm btn-outline-secondary">Almoço</a>
                <a href="?a=lista_reservas&data=<?= $data_get ?>&f=jantar" type="button" class="btn btn-sm btn-outline-secondary">Jantar</a>
                <a href="?a=lista_reservas" type="button" class="btn btn-sm btn-outline-secondary">Todas</a>
            </div>

        </div>
    </div>


    <div class="container">
        <div class="row">

            <div class="table-responsive small mt-3">
                <table class="table table-striped table-sm" id="reserva">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" scope="col">Cliente</th>
                            <th scope="col">Data</th>
                            <th scope="col">Periodo</th>
                            <th scope="col">Pessoas</th>
                            <th scope="col">Mesa</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actualizada em </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lista_reservas)): ?>
                            <?php foreach ($lista_reservas as $reserva): ?>
                                <tr>
                                    <td class="text-center"><?= $reserva->cliente ?></td>
                                    <td><?= $reserva->data ?> - <?= $reserva->hora_inicio ? date('H:i', strtotime($reserva->hora_inicio)) : '-----' ?> </td>
                                    <td><?= $reserva->tipo_refeicao ?></td>
                                    <td><?= $reserva->n_pessoa ? $reserva->n_pessoa : '-' ?></td>
                                    <td><?= $reserva->mesa ? $reserva->mesa : '-' ?></td>





                                    <td class="text-primary" style="cursor: pointer;">
                                        <a href="?a=detalhe_reserva&id=<?= \core\classes\Store::aesEcriptar($reserva->id_reversa) ?>"> <?= $reserva->status ?></a>

                                    </td>



                                    <td>

                                        <?php if ($_SESSION['admin']): ?>
                                            <?= $reserva->updated_at?>
                                        <?php else: ?>
                                            <a href=""><i class="mx-1 far fa-circle-check text-success"></i></a>
                                            <a href=""><i class="mx-1 fas fa-cancel text-danger"></i></a>
                                            <a href=""><i class="mx-1 fas fa-edit text-info"></i></a>
                                        <?php endif; ?>


                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
        
    </div>

</main>

<!-- Modal Status -->
<div class="modal fade" id="modalEstadoEvento" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="exampleModalLabel">Alterar estado da Reserva</h5>
            </div>
            <div class="modal-body">

                <div class="text-center">
                    <?php foreach (STATUS_RESERVA as $estado) : ?>
                        <?php if ($reserva->status == $estado) : ?>
                            <p><?= $estado ?> - <?= $reserva->status ?></p>
                        <?php else : ?>
                            <p><a href="" class="alterar-estado" id="alterar-estado" onclick="eventoEstado(<?= $estado ?>)" data-estado="<?= $estado ?>"><?= $estado ?></a></p>
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
    // modal do estado
    function abrirModalEstado(eventoId) {
        $('#modalEstadoEvento').modal('show');
        // Armazena o ID do evento no botão de alterar estado dentro do modal

        $('.alterar-estado').on('click', function() {
            var eventoEstado = $(this).data('estado');
            var link = '?a=reserva_alterar_estado&id=' + eventoId + '&s=' + eventoEstado;
            $('.alterar-estado').attr('href', link);
        });
    }
</script>