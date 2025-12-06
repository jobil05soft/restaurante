<style>
    .text-normal {
        color: #FFFFFF !important;
    }

    .text-cinza {
        color: #B3B3B3 !important;
    }

    table>thead {
        background-color: #002b36 !important;
    }

    table>tr {
        color: #1a1a1a;
    }

    table {
        color: #FFFFFF !important;
    }
</style>
<main class="main">

    <!-- Page Title -->
    <div class="page-title position-relative" data-aos="fade"
        style="background-image: url(assets/img/page-title-bg.webp);">

    </div><!-- End Page Title -->
    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

        <!-- Section Title -->
        <div class="container section-title py-2" data-aos="fade-up">
            <h2>Resumo</h2>
            <p>Resumo da Reserva<br></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">

            <table class="table">
                <thead>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Numero da Mesa</th>
                    <th>Pessoas</th>
                    <th>Capacidade</th>
                    <th>Data</th>
                    <th>Valor da Reserva</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $dados_reserva['nome'] ?></td>
                        <td><?= $dados_reserva['email'] ?></td>
                        <td><?= $dados_reserva['telefone'] ?></td>
                        <td><?= $dados_mesa['numero_mesa'] ?></td>
                        <td><?= $dados_reserva['numero_pessoa'] ?></td>
                        <td><?= $dados_mesa['capacidade'] ?></td>
                        <td><?= $dados_reserva['data'] ?> - <?= $dados_reserva['hora'] ?></td>
                        <td><?= $dados_reserva['total'] . ' Kz' ?></td>
                    </tr>
                </tbody>
            </table>


            <div class="row mt-4">

                <div class="col-12">
                    <a href="?a=cancelar_reserva" class="btn btn-danger me-4">Cancelar Reserva</a>
                    <a href="?a=confirmar_reserva" class="btn btn-primary">Confirmar Reserva</a>
                </div>
            </div>
        </div>

    </section><!-- /Starter Section Section -->

</main>