<main>
    <article>
        <section class="containerSection">
            <div class="divGrid">
                <?php if ($animeObtenido): ?>
                    <img class="imgAnime" src="data:image/jpeg;base64,<?= $animeObtenido->__get('portada'); ?>" alt="<?= $animeObtenido->__get('nombreAnime'); ?>">

                    <a href="animeController.php?id=<?= $idAnime; ?>" class="copyUrl" id="botonCopiarURL">
                        <span>Copy URL</span>
                    </a>

                    <h2><?= $animeObtenido->__get('nombreAnime'); ?></h2>

                    <div class="buttons">
                        <button class="status" disabled><?= $animeObtenido->__get('estado') == 'FINISHED' ? "<span class='finished' >" . $animeObtenido->__get('estado') . "</span>" : "<span class='releasing' >" . $animeObtenido->__get('estado') . "</span>" ?></button>
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <button type="submit" class="saveOnList" data-bs-toggle="modal" data-bs-target="#seleccionarLista">
                                <span>Save on list</span>
                            </button>
                        <?php else: ?>
                            <button class="saveOnList" disabled>
                                <i class="bi bi-lock-fill"></i><span>Save on list</span>
                            </button>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <?php if ($tieneMeGusta): ?>
                                <form action="#" method="post">
                                    <input type="hidden" name="idAnime" value="<?= $idAnime; ?>">
                                    <button type="submit" name="quitarMeGusta" class="like">
                                        <i class="bi bi-heart-fill"></i><span><?= $animeObtenido->__get('cantidadMeGusta'); ?></span>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="#" method="post">
                                    <input type="hidden" name="idAnime" value="<?= $idAnime; ?>">
                                    <button type="submit" name="meGusta" class="like">
                                        <i class="bi bi-heart"></i><span><?= $animeObtenido->__get('cantidadMeGusta'); ?></span>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="like" disabled>
                                <i class="bi bi-lock-fill"></i><span><?= $animeObtenido->__get('cantidadMeGusta'); ?></span>
                            </button>
                        <?php endif; ?>
                    </div>
                    <p class="description"><?= $animeObtenido->__get('sinopsis'); ?></p>

                    <ul class="list">
                        <li><span class="b">Format : </span><span><?= $animeObtenido->__get('tipo'); ?></span></li>
                        <li><span class="b">Episodes : </span><span><?= $animeObtenido->__get('capitulos'); ?></span></li>
                        <li><span class="b">Genre : </span><span><?= $animeObtenido->__get('genero'); ?></span></li>
                        <li><span class="b">Start date : </span><span><?= $animeObtenido->__get('fechaInicio'); ?></span></li>
                        <?php
                        if ($animeObtenido->__get("fechaFin") !== null && $animeObtenido->__get("tipo") == "TV") {
                        ?>
                            <li><span class="b">End date : </span><span><?= $animeObtenido->__get("fechaFin"); ?></span></li>
                        <?php
                        }
                        ?>
                    </ul>

                    <details class="comments">
                        <summary>See comments</summary>
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <div class="publish">
                                <form action="#" method="post" id="formularioComentario">
                                    <textarea rows="4" maxlength="200" name="comentario" id="comentario"></textarea>
                                    <div id="comentarioError" class="error"></div>
                                    <?= $comentarioResultado; ?>
                                    <input type="submit" value="Post">
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="publish">
                                <form action="#" method="post" id="formularioComentario">
                                    <textarea disabled rows="4" maxlength="200" name="comentario" id="comentario"></textarea>
                                    <button class="buttonDisabled" disabled><i class="bi bi-lock-fill"></i> Post</button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($comentarios as $comentario): ?>
                            <div class="comment">
                                <img class="profilePhoto" src="<?= !empty($comentario['fotoPerfil']) ? 'data:image/jpeg;base64,' . base64_encode($comentario['fotoPerfil']) : '../view/assets/images/default-profile.png'; ?>" alt="<?= $comentario['nombreUsuario']; ?>">
                                <div>
                                    <div>
                                        <p> <strong><span><?= $comentario['nombreUsuario']; ?></span></strong> </p>
                                        <p><strong><span><?= $comentario['fechaPublicacion']; ?></span></strong></p>
                                    </div>
                                    <p class="commentText"><?= htmlspecialchars($comentario['texto']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </details>
                    <p class="textError"> <?= $resultadoFormulario; ?> </p>
                <?php else: ?>
                    <p>No details were found for this anime.</p>
                <?php endif; ?>
            </div>
        </section>
    </article>

    <!-- Modal para seleccionar la lista donde añadir el anime -->
    <div class="modal fade" id="seleccionarLista" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border: none;">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="seleccionarListaLabel">Select a list</h5>
                </div>
                <form action="#" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="idAnime" value="<?= $idAnime; ?>">
                        <div class="mb-3">
                            <label for="listSelect" class="form-label">Choose the list in which you want to save the anime :</label>
                            <select name="idLista" id="listSelect" class="form-select">
                                <?php foreach ($listas as $lista): ?>
                                    <!-- Obtenemos las listas que no tengan ese anime almacenado -->
                                    <?php if (!in_array($lista->__get('idLista'), $listasConAnime)): ?>
                                        <option value="<?= $lista->__get('idLista'); ?>">
                                            <?= $lista->__get('nombreLista'); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" name="guardarEnLista" class="btnAcceptCancel acceptButton">Save</button>
                        <button type="button" class="btnAcceptCancel cancelButton" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</main>

<script src="../view/assets/scripts/obtenerURL.js"></script>

<script src="../view/assets/scripts/validarInput.js"></script>

<script src="../view/assets/scripts/comprobarComentario.js"></script>