<main>
    <article class="article">

        <section class="container my-4">
            <div class="row justify-content-around align-items-center text-center">
                <div class="col-4">
                    <h1>Clients</h1>
                </div>
            </div>
        </section>

        <section class="container containerHeader">
            <div class="row justify-content-between align-items-center text-center">
                <div class="col-4">ID</div>
                <div class="col-4">Username</div>
                <div class="col-4">Options</div>
            </div>
        </section>

        <?php if (count($clientes) > 0): ?>
            <?php foreach ($clientes as $cliente): ?>
                <section class="container containerSection my-5">
                    <div class="row justify-content-between align-items-center text-center">
                        <div class="col-4"><?= $cliente['idUsuario']; ?></div>
                        <div class="col-4"><?= $cliente['nombreUsuario']; ?></div>
                        <div class="col-4">
                            <div class="button-container d-flex justify-content-around">
                                <button class="deleteUser btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-id="<?= $cliente['idUsuario']; ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="row justify-content-between align-items-center text-center my-5">
                <p class="text">No clients registered.</p>
            </div>
        <?php endif; ?>
    </article>

    <!-- Modal para borrar un usuario -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border: none;">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="deleteUserModalLabel">Delete user</h5>
                </div>
                <form action="#" method="POST">
                    <div class="modal-body text-center">
                        <p>¿Are you sure delete this user?</p>
                        <input type="hidden" name="idUsuario" id="obtenerIdUsuario">
                    </div>
                    <div class="modal-footer justify-content-around">
                        <button type="submit" class="btnAcceptCancel deleteButton" name="borrarUsuario">Delete</button>
                        <button type="button" class="btnAcceptCancel cancelButton" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

<script src="../view/assets/scripts/opcionesAdmin.js"></script>