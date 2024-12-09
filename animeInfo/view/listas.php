<main>
    <article class="article">
        <section class="container my-4">
            <div class="row justify-content-around align-items-center text-center">
                <div class="col-4">
                    <h1>Lists</h1>
                </div>
                <div class="col-1">
                    <p class="text"> <?= $resultado; ?> </p>
                </div>
                <div class="col-5">
                    <button class="createList" data-bs-toggle="modal" data-bs-target="#createListModal">
                        <span>Create list</span>
                    </button>
                </div>
            </div>
        </section>

        <section class="container containerHeader">
            <div class="row justify-content-between align-items-center text-center">
                <div class="col-3">Title</div>
                <div class="col-3">Creation date</div>
                <div class="col-5">Options</div>
            </div>
        </section>

        <?php if (count($listas) > 0): ?>
            <?php foreach ($listas as $lista): ?>
                <section class="container containerSection my-5">
                    <div class="row justify-content-between align-items-center text-center">
                        <div class="col-3"><?= $lista->__get("nombreLista"); ?></div>
                        <div class="col-3"><?= $lista->__get("fechaCreacion"); ?></div>
                        <div class="col-5">
                            <div class="button-container d-flex justify-content-around">
                                <form action="../controller/listaController.php" method="post">
                                    <button class="enter btn btn-outline-primary" type="submit" name="idLista" value="<?= $lista->__get('idLista'); ?>"><i class="bi bi-folder-fill"></i></button>
                                </form>
                                <button class="modify btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editListModal" data-nombre="<?= $lista->__get('nombreLista'); ?>" data-id="<?= $lista->__get('idLista'); ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="deleteList btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteListModal" data-id="<?= $lista->__get('idLista'); ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="row justify-content-between align-items-center text-center my-5">
                <p class="text">You have no lists created.</p>
            </div>
        <?php endif; ?>

    </article>

    <!-- Modal para crear una lista -->
    <div class="modal fade" id="createListModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border: none;">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="createListModalLabel">Create new list</h5>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="crearListaForm">
                        <div class="mb-3 text-center">
                            <input type="text" class="form-control no-border" name="nombreLista" id="nombreLista" placeholder="Insert list name">
                            <span class="errorMessage" id="crearListaError"></span>
                        </div>
                </div>
                <div class="modal-footer justify-content-around">
                    <button type="submit" class="btnAcceptCancel acceptButton" name="crear">Create</button>
                    <button type="button" class="btnAcceptCancel cancelButton" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar una lista -->
    <div class="modal fade" id="editListModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border: none;">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="editListModalLabel">Edit list</h5>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="editarListaForm">
                        <input type="hidden" name="idListaEditar" id="idListaEditar">

                        <div class="mb-3 text-center">
                            <input type="text" class="form-control no-border" name="nombreLista" id="editarNombreLista" placeholder="Insert new list name">
                            <span class="errorMessage" id="editarListaError"></span>
                        </div>
                </div>
                <div class="modal-footer justify-content-around">
                    <button type="submit" class="btnAcceptCancel acceptButton" name="editar">Edit</button>
                    <button type="button" class="btnAcceptCancel cancelButton" data-bs-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para borrar una lista -->
    <div class="modal fade" id="deleteListModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border: none;">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="deleteListModalLabel">Delete list</h5>
                </div>
                <form action="#" method="POST">
                    <div class="modal-body text-center">
                        <p>Are you sure you want to remove this list?</p>
                        <p id="deletenombreLista"></p>
                        <input type="hidden" name="idLista" id="idLista">
                    </div>
                    <div class="modal-footer justify-content-around">
                        <button type="submit" class="btnAcceptCancel deleteButton" name="borrar">Delete</button>
                        <button type="button" class="btnAcceptCancel cancelButton" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="../view/assets/scripts/opcionesListas.js"></script>

<script src="../view/assets/scripts/validarInput.js"></script>

<script src="../view/assets/scripts/comprobarListas.js"></script>