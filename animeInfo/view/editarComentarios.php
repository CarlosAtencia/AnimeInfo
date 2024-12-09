<main>
    <article>
        <section class="container text-center my-5">
            <span class="titlePage">Edit comments</span>
        </section>
        <?php if (isset($error)): ?>
            <div class="errorMessage"><?= $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($comentariosAnime)): ?>
            <?php foreach ($comentariosAnime as $anime): ?>
                <section class="containerSection">
                    <div class="divGrid">
                        <img class="imgAnime" src="data:image/jpeg;base64,<?= base64_encode($anime['portada']); ?>" alt="<?= $anime['nombreAnime']; ?>">
                        <h2><?= $anime['nombreAnime']; ?></h2>

                        <details class="comments" title="Comentarios">
                            <summary>See comments</summary>

                            <?php if (!empty($anime['comentarios'])): ?>
                                <?php foreach ($anime['comentarios'] as $comentario): ?>
                                    <div class="comment">
                                        <div>
                                            <p class="commentTitle"><?= $comentario['fechaPublicacion']; ?></p>
                                            <p class="commentText"><?= $comentario['texto']; ?></p>
                                            <form action="" method="POST" class="formDelete">
                                                <input type="hidden" name="idComentario" value="<?= $comentario['idComentario']; ?>">
                                                <button class="delete deleteButton " type="submit" name="eliminarComentario"><span>Delete</span></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </details>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text">You have no comments</p>
        <?php endif; ?>
    </article>
</main>