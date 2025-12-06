<main class="main">

    <!-- Page Title -->
    <div class="page-title position-relative" data-aos="fade" style="background-image: url(assets/img/page-title-bg.webp);">
        <div class="container position-relative">
            <p>Temos para si um lista de todos os pratos que são feitos no nosso restaurante</p>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="?a=inicio">Home</a></li>
                    <li class="current">Cardápio</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->




    <!-- Menu Section -->
    <section id="menu" class="menu section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Cardápio</h2>
            <p>Nosso Cardápio</p>
        </div><!-- End Section Title -->

        <div class="container isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

            <div class="row" data-aos="fade-up" data-aos-delay="100">
                <div class="col-lg-12 d-flex justify-content-center">
                    <ul class="menu-filters isotope-filters">
                        <li data-filter="*" class="filter-active"><a href="?a=cardapio">Todos</a></li>

                        <?php foreach ($categorias as $categoria): ?>
                            <li data-filter=".filter-<?= $categoria ?>">
                                <a href="?a=cardapio&c=<?= $categoria ?>" class="text-light">
                                    <?= ucfirst(preg_replace("/\_/", " ", $categoria)) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>

                        <!-- 
                             <a href="?a=loja&c=todos" class="btn btn-primary">Todos</a>
            <?php foreach ($categorias as $categoria) : ?>
                <a href="?a=loja&c=<?= $categoria ?>" class="btn btn-primary">
                    <?= ucfirst(preg_replace("/\_/", " ", $categoria)) ?>
                </a>
            <?php endforeach; ?>
                         -->

                    </ul>
                </div>
            </div><!-- Menu Filters -->

            <div class="row isotope-container" data-aos="fade-up" data-aos-delay="200">

                <?php foreach ($itens_cardapio as $item): ?>
                    <div class="col-lg-6 menu-item isotope-item filter-<?= $item->categoria ?>">
                        <img src="assets/img/menu/<?= $item->imagem ?>" class="menu-img" alt="<?= $item->imagem ?>">
                        <div class="menu-content">
                            <a href=""><?= $item->nome_item ?></a><span><?= $item->preco ?></span>
                        </div>
                        <div class="menu-ingredients">
                            <?= $item->descricao ?>
                        </div>
                        <div class="menu-ingredients mt-2">
                            <button class="btn btn-info btn-sm" onclick="adicionar_carrinho(<?= $item->id_item ?>)"><i class="fas fa-shopping-cart me-2"></i>Fazer Pedido</button>

                        </div>
                    </div><!-- Menu Item -->
                <?php endforeach; ?>




            </div><!-- Menu Container -->

        </div>

    </section><!-- /Menu Section -->
</main>