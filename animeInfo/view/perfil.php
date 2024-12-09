<main>
    <article>
        <section class="containerSection">
            <div class="profilePhoto">
                <img src="data:image/jpeg;base64,<?= $usuario->__get('fotoPerfil'); ?>">
            </div>
            <a href="./editarPerfilController.php" class="modify"><span>Edit profile</span></a>
            <a href="./editarComentariosController.php" class="modify"><span>Edit comments</span></a>
            <?php
            if ($_SESSION['rol'] == 'admin') {
            ?>
                <a href="./opcionesAdminController.php" class="adminOptions"><span>Admin options</span></a>
            <?php
            }
            ?>
            <a href="./cerrarSesion.php" class="logOut"><span>Log out</span></a>

            <button class="deleteAccount" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                <span>Delete account</span>
            </button>
        </section>
    </article>

    <!-- Modal para borrar cuenta -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border: none;">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete account</h5>
                </div>
                <form action="#" method="POST">
                    <div class="modal-body text-center">
                        <p>¿Are you sure you want to delete your account? This action cannot be undone.</p>
                        <input type="hidden" name="idUsuario" value="<?= $usuario->__get('id'); ?>">
                    </div>
                    <div class="modal-footer justify-content-around">
                        <button class="btnAcceptCancel deleteButton" type="submit" name="borrarCuenta">Delete</button>
                        <button class="btnAcceptCancel cancelButton" type="button" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>