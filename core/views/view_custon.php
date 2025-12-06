<main class="main">

    <!-- Page Title -->
    <div class="page-title position-relative" data-aos="fade"
        style="background-image: url(assets/img/page-title-bg.webp);">

    </div><!-- End Page Title -->
    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

        <!-- Section Title -->
        <div class="container section-title py-2" data-aos="fade-up">
            <h2>Seu Pedido</h2>
            <p>Resumo<br></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">


            <div class="row">
                <div class="col">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Item</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-end">Valor total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $index = 0;
                            $total_rows = count($carrinho);
                            ?>
                            <?php foreach ($carrinho as $produto) : ?>
                                <?php if ($index < $total_rows - 1) : ?>

                                    <!-- lista dos produtos -->
                                    <tr class="">
                                        <td><img src="assets/img/menu/<?= $produto['imagem']; ?>" class="img-fluid" width="50px"></td>
                                        <td class="align-middle ">
                                            <h5 class=""><?= $produto['titulo'] ?></h5>
                                        </td>
                                        <td class="text-center align-middle">
                                            <h5><?= $produto['quantidade'] ?></h5>
                                        </td>
                                        <td class="text-end align-middle">
                                            <h4><?= number_format($produto['preco'], 2, ',', '.') ?></h4>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="?a=remover_item_carrinho&id_item=<?= core\classes\Store::aesEcriptar($produto['id_item']) ?>" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></a>
                                        </td>
                                    </tr>

                                <?php else : ?>

                                    <!-- total -->
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end">
                                            <h3>Total:</h3>
                                        </td>
                                        <td class="text-end">
                                            <h3><?= number_format($produto, 2, ',', '.')  ?></h3>
                                        </td>
                                        <td></td>
                                    </tr>

                                <?php endif; ?>
                                <?php $index++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section><!-- /Starter Section Section -->

</main>