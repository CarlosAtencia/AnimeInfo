<main>
    <article>
        <section class="container text-center my-5">
            <span class="titleList"><?= $nombreLista->__get('nombreLista'); ?></span>
        </section>

        <?php
        if ($animesLista) {
        ?>
            <section class="container-fluid containerAnime mr-mr-4 pb-4" id="resultadosAnime">
                <?php
                foreach ($animesLista as $anime) {
                ?>
                    <div class="anime" data-id="<?= $anime->__get('idAnime'); ?> ">
                        <form action="" method="POST">
                            <input type="hidden" name="idLista" value="<?= $idLista; ?>">
                            <input type="hidden" name="idAnime" value="<?= $anime->__get('idAnime'); ?>">
                            <button type="submit" class="deleteAnime deleteButton btn btn-outline-danger" name="borrarAnimeLista">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>

                        <img src="data:image/jpeg;base64,<?= $anime->__get('portada'); ?>" alt="<?= $anime->__get('nombreAnime'); ?>">
                        <h5 class="my-3"><?= $anime->__get('nombreAnime'); ?></h5>
                    </div>
                <?php
                }
                ?>
            </section>
        <?php
        } else {
        ?>
            <section class="container text-center my-5">
                <span class="text">You have no animes in this list</span>
            </section>
        <?php
        }
        ?>
    </article>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>