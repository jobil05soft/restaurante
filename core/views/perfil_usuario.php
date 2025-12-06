

    <section class="section" id="">

        <?php include('layouts/perfil_navegacao.php'); ?>
        <table class="table table-striped my-2">
            <?php foreach ($dados_usuario as $key => $valor): ?>
                <tr>
                    <td class="text-end" width="40%"><?= $key ?></td>
                    <td width="60%"><strong><?= $valor ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>