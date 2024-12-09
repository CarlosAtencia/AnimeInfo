<main>
    <article>
        <section class="container text-center my-5">
            <span class="titleFavorites">Favorites</span>
        </section>
        <?php if (!empty($animesMeGusta)): ?>
            <section class="container-fluid containerAnime mr-mr-4 pb-4" id="resultadosAnime">

                <?php foreach ($animesMeGusta as $anime): ?>
                    <div class="anime" data-id="<?= $anime->__get('idAnime'); ?> ">

                        <form action="" method="POST">
                            <input type="hidden" name="idAnime" value="<?= $anime->__get('idAnime'); ?>">
                            <button type="submit" class="deleteAnime btn btn-outline-danger" name="quitarMeGusta">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>

                        <img src="data:image/jpeg;base64,<?= $anime->__get('portada'); ?>" alt="<?= $anime->__get('nombreAnime'); ?>">
                        <h5 class="my-3"><?= $anime->__get('nombreAnime'); ?></h5>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <section>
                <p class="text">You have no anime in favorites</p>
            </section>
        <?php endif; ?>
    </article>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>